<?php

namespace App\Http\Controllers;

use App\Entities\Customer;
use App\Entities\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Mail;

class WalletSoapController extends Controller
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function registerCustomer($document, $name, $email, $phone)
    {
        try {
            // Validación de tipos de datos
            if (!is_numeric($document)) {
                throw new \InvalidArgumentException('El documento debe ser un valor numérico.');
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('El correo electrónico no es válido.');
            }

            // Validación de datos
            if (empty($document) || empty($name) || empty($email) || empty($phone)) {
                return $this->formatResponse(false, '01', 'Todos los campos son obligatorios', null);
            }

            // Verificar si el cliente ya existe
            $existingCustomer = $this->entityManager->getRepository(Customer::class)->findOneBy([
                'document' => $document,
                'email' => $email
            ]);
            if ($existingCustomer) {
                return $this->formatResponse(false, '03', 'Ya existe un cliente con el mismo documento o correo electrónico', null);
            }

            // Crear y guardar el cliente
            $customer = new Customer();
            $customer->setDocument($document);
            $customer->setName($name);
            $customer->setEmail($email);
            $customer->setPhone($phone);

            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            return $this->formatResponse(true, '00', 'Cliente registrado exitosamente', $customer);
        } catch (\InvalidArgumentException $e) {
            return $this->formatResponse(false, '04', 'Entrada no válida: ' . $e->getMessage(), null);
        } catch (\Exception $e) {
            return $this->formatResponse(false, '02', 'Error al registrar el cliente: ' . $e->getMessage(), null);
        }
    }

    public function rechargeWallet($document, $phone, $amount)
    {
        try {
            // Validación de tipos de datos
            if (!is_numeric($document) || !is_numeric($amount) || $amount <= 0) {
                throw new \InvalidArgumentException('El documento y el monto deben ser valores numéricos positivos.');
            }

            // Validación de datos
            if (empty($document) || empty($phone) || empty($amount)) {
                return $this->formatResponse(false, '01', 'Todos los campos son obligatorios', null);
            }

            // Verificar si el cliente existe
            $customer = $this->entityManager->getRepository(Customer::class)->findOneBy([
                'document' => $document,
                'phone' => $phone
            ]);
            if (!$customer) {
                return $this->formatResponse(false, '06', 'Cliente no encontrado', null);
            }

            // Recargar la billetera
            $customer->addBalance($amount);
            $this->entityManager->flush();

            return $this->formatResponse(true, '00', 'Recarga exitosa', ['balance' => $customer->getBalance()]);
        } catch (\InvalidArgumentException $e) {
            return $this->formatResponse(false, '04', 'Entrada no válida: ' . $e->getMessage(), null);
        } catch (\Exception $e) {
            return $this->formatResponse(false, '02', 'Error al recargar la billetera: ' . $e->getMessage(), null);
        }
    }

    public function pay($document, $amount)
    {
        try {
            // Validación de tipos de datos
            if (!is_numeric($document) || !is_numeric($amount) || $amount <= 0) {
                throw new \InvalidArgumentException('El documento y el monto deben ser valores numéricos positivos.');
            }

            // Validación de datos
            if (empty($document) || empty($amount)) {
                return $this->formatResponse(false, '01', 'Todos los campos son obligatorios', null);
            }

            // Verificar si el cliente existe
            $customer = $this->entityManager->getRepository(Customer::class)->findOneBy(['document' => $document]);
            if (!$customer) {
                return $this->formatResponse(false, '06', 'Cliente no encontrado', null);
            }

            // Verificar saldo suficiente
            if ($customer->getBalance() < $amount) {
                return $this->formatResponse(false, '07', 'Saldo insuficiente', null);
            }

            // Generar token de confirmación
            $token = rand(100000, 999999);
            $sessionId = uniqid();

            // Guardar transacción pendiente
            $transaction = new Transaction();
            $transaction->setCustomer($customer);
            $transaction->setType('pago');
            $transaction->setAmount($amount);
            $transaction->setStatus('pendiente');
            $transaction->setSessionId($sessionId);
            $transaction->setConfirmationToken($token);

            $this->entityManager->persist($transaction);
            $this->entityManager->flush();

            // Enviar token por correo electrónico
            Mail::raw("Su código de confirmación es: $token", function ($message) use ($customer) {
                $message->to($customer->getEmail())
                        ->subject('Código de Confirmación de Pago');
            });

            return $this->formatResponse(true, '00', 'Token de confirmación enviado al correo electrónico', ['session_id' => $sessionId]);
        } catch (\InvalidArgumentException $e) {
            return $this->formatResponse(false, '04', 'Entrada no válida: ' . $e->getMessage(), null);
        } catch (\Exception $e) {
            return $this->formatResponse(false, '02', 'Error al procesar el pago: ' . $e->getMessage(), null);
        }
    }

    public function confirmPayment($sessionId, $token)
    {
        try {
            // Validación de tipos de datos
            if (empty($sessionId) || !is_numeric($token)) {
                throw new \InvalidArgumentException('El ID de sesión y el token deben ser proporcionados.');
            }

            // Validar la transacción pendiente
            $transaction = $this->entityManager->getRepository(Transaction::class)->findOneBy(['session_id' => $sessionId, 'confirmation_token' => $token, 'status' => 'pendiente']);
            if (!$transaction) {
                return $this->formatResponse(false, '08', 'Token de confirmación o ID de sesión incorrectos.', null);
            }

            // Obtener el cliente asociado
            $customer = $transaction->getCustomer();

            // Descontar el saldo de la billetera
            $amount = $transaction->getAmount();
            $customer->setBalance($customer->getBalance() - $amount);

            // Actualizar el estado de la transacción
            $transaction->setStatus('completada');

            $this->entityManager->flush();

            return $this->formatResponse(true, '00', 'Pago confirmado y saldo descontado.', null);
        } catch (\InvalidArgumentException $e) {
            return $this->formatResponse(false, '04', 'Entrada no válida: ' . $e->getMessage(), null);
        } catch (\Exception $e) {
            return $this->formatResponse(false, '02', 'Error al confirmar el pago: ' . $e->getMessage(), null);
        }
    }

    public function getBalance($document, $phone)
    {
        try {
            // Validación de tipos de datos
            if (!is_numeric($document) || empty($phone)) {
                throw new \InvalidArgumentException('El documento debe ser numérico y el número de teléfono no puede estar vacío.');
            }

            // Validación de datos
            if (empty($document) || empty($phone)) {
                return $this->formatResponse(false, '01', 'Todos los campos son obligatorios', null);
            }

            // Verificar si el cliente existe
            $customer = $this->entityManager->getRepository(Customer::class)->findOneBy(['document' => $document, 'phone' => $phone]);
            if (!$customer) {
                return $this->formatResponse(false, '06', 'Cliente no encontrado', null);
            }

            // Obtener el saldo de la billetera
            $balance = $customer->getBalance();

            return $this->formatResponse(true, '00', 'Consulta de saldo exitosa', ['balance' => $balance]);
        } catch (\InvalidArgumentException $e) {
            return $this->formatResponse(false, '04', 'Entrada no válida: ' . $e->getMessage(), null);
        } catch (\Exception $e) {
            return $this->formatResponse(false, '02', 'Error al consultar el saldo: ' . $e->getMessage(), null);
        }
    }

    private function formatResponse($success, $errorCode, $errorMessage, $data)
    {
        return [
            'success' => $success,
            'cod_error' => $errorCode,
            'message_error' => $errorMessage,
            'data' => $data
        ];
    }
}
