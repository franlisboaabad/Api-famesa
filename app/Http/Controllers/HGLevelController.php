<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Location;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Workflow;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HGLevelController extends Controller
{
    /**
     * Recibe y procesa datos de HGLevel
     */
    public function receiveData(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            // Log para debug
            Log::info('Datos recibidos:', [
                'type' => gettype($data),
                'content' => $data
            ]);

            // Si los datos vienen como string JSON, parsearlos
            if (is_string($data)) {
                $data = json_decode($data, true);
            }

            // Si aún no es array, intentar obtener el contenido raw
            if (!is_array($data)) {
                $rawData = $request->getContent();
                $data = json_decode($rawData, true);
            }

            // Validar que sea un array
            if (!is_array($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Los datos deben ser un array JSON válido'
                ], 400);
            }

            $processedContacts = [];

            DB::beginTransaction();

            foreach ($data as $contactData) {
                $processedContact = $this->processContactData($contactData);
                $processedContacts[] = $processedContact;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Datos procesados correctamente',
                'processed_contacts' => count($processedContacts),
                'data' => $processedContacts
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error procesando datos de HGLevel: ' . $e->getMessage(), [
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error procesando los datos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesa los datos de un contacto individual
     */
    private function processContactData(array $contactData): array
    {
        $contactId = $contactData['contact_id'] ?? null;

        if (!$contactId) {
            throw new \Exception('contact_id es requerido');
        }

        // Procesar Location si existe
        $locationId = null;
        if (isset($contactData['location'])) {
            $locationId = $this->processLocation($contactData['location']);
        }

        // Procesar Invoice si existe
        $invoiceId = null;
        if (isset($contactData['invoice'])) {
            $invoiceId = $this->processInvoice($contactData['invoice']);
        }

        // Procesar Workflow si existe
        $workflowId = null;
        if (isset($contactData['workflow'])) {
            $workflowId = $this->processWorkflow($contactData['workflow']);
        }

        // Crear o actualizar Contact
        $contact = Contact::updateOrCreate(
            ['contact_id' => $contactId],
            [
                'first_name' => $contactData['first_name'] ?? '',
                'last_name' => $contactData['last_name'] ?? '',
                'full_name' => $contactData['full_name'] ?? '',
                'email' => $contactData['email'] ?? '',
                'phone' => $contactData['phone'] ?? null,
                'tags' => $contactData['tags'] ?? null,
                'address1' => $contactData['address1'] ?? null,
                'city' => $contactData['city'] ?? null,
                'state' => $contactData['state'] ?? null,
                'country' => $contactData['country'] ?? null,
                'timezone' => $contactData['timezone'] ?? null,
                'date_created' => isset($contactData['date_created']) ?
                    \Carbon\Carbon::parse($contactData['date_created']) : null,
                'contact_source' => $contactData['contact_source'] ?? null,
                'full_address' => $contactData['full_address'] ?? null,
                'contact_type' => $contactData['contact_type'] ?? null,
                'location_id' => $locationId,
                'invoice_id' => $invoiceId,
                'workflow_id' => $workflowId,
                'custom_data' => $contactData['customData'] ?? null,
                'attribution_source' => $contactData['attributionSource'] ?? null
            ]
        );

        return [
            'contact_id' => $contact->contact_id,
            'id' => $contact->id,
            'status' => 'processed'
        ];
    }

    /**
     * Procesa datos de Location
     */
    private function processLocation(array $locationData): string
    {
        $locationId = $locationData['id'] ?? null;

        if (!$locationId) {
            throw new \Exception('Location ID es requerido');
        }

        Location::updateOrCreate(
            ['location_id' => $locationId],
            [
                'name' => $locationData['name'] ?? '',
                'address' => $locationData['address'] ?? '',
                'city' => $locationData['city'] ?? '',
                'state' => $locationData['state'] ?? null,
                'country' => $locationData['country'] ?? '',
                'postal_code' => $locationData['postalCode'] ?? null,
                'full_address' => $locationData['fullAddress'] ?? ''
            ]
        );

        return $locationId;
    }

    /**
     * Procesa datos de Invoice
     */
    private function processInvoice(array $invoiceData): string
    {
        $invoiceId = $invoiceData['_id'] ?? null;

        if (!$invoiceId) {
            throw new \Exception('Invoice ID es requerido');
        }

        $invoiceDetails = $invoiceData['_data'] ?? [];

        // Crear o actualizar Invoice
        Invoice::updateOrCreate(
            ['invoice_id' => $invoiceId],
            [
                'alt_id' => $invoiceDetails['altId'] ?? null,
                'alt_type' => $invoiceDetails['altType'] ?? null,
                'company_id' => $invoiceDetails['companyId'] ?? null,
                'name' => $invoiceDetails['name'] ?? '',
                'invoice_number' => $invoiceDetails['invoiceNumber'] ?? '',
                'currency' => $invoiceDetails['currency'] ?? '',
                'status' => $invoiceDetails['status'] ?? '',
                'amount_paid' => $invoiceDetails['amountPaid'] ?? 0,
                'total' => $invoiceDetails['total'] ?? 0,
                'invoice_total' => $invoiceDetails['invoiceTotal'] ?? 0,
                'amount_due' => $invoiceDetails['amountDue'] ?? 0,
                'title' => $invoiceDetails['title'] ?? null,
                'issue_date' => isset($invoiceDetails['issueDate']) ?
                    \Carbon\Carbon::parse($invoiceDetails['issueDate']) : null,
                'due_date' => isset($invoiceDetails['dueDate']) ?
                    \Carbon\Carbon::parse($invoiceDetails['dueDate']) : null,
                'terms_notes' => $invoiceDetails['termsNotes'] ?? null,
                'live_mode' => $invoiceDetails['liveMode'] ?? true,
                'deleted' => $invoiceDetails['deleted'] ?? false,
                'business_details' => $invoiceDetails['businessDetails'] ?? null,
                'contact_details' => $invoiceDetails['contactDetails'] ?? null,
                'discount' => $invoiceDetails['discount'] ?? null,
                'payment_methods' => $invoiceDetails['paymentMethods'] ?? null,
                'configuration' => $invoiceDetails['configuration'] ?? null,
                'late_fees_configuration' => $invoiceDetails['lateFeesConfiguration'] ?? null,
                'reminders_configuration' => $invoiceDetails['remindersConfiguration'] ?? null,
                'meta' => $invoiceDetails['meta'] ?? null,
                'total_summary' => $invoiceDetails['totalSummary'] ?? null,
                'invoice_url' => $invoiceData['url'] ?? null,
                'sender_name' => $invoiceData['sender_name'] ?? null,
                'sender_email' => $invoiceData['sender_email'] ?? null,
                'sent_at' => isset($invoiceDetails['sentAt']) ?
                    \Carbon\Carbon::parse($invoiceDetails['sentAt']) : null,
                'sent_by' => $invoiceDetails['sentBy'] ?? null,
                'sent_from' => $invoiceDetails['sentFrom'] ?? null,
                'sent_to' => $invoiceDetails['sentTo'] ?? null,
                'updated_by' => $invoiceDetails['updatedBy'] ?? null
            ]
        );

        // Procesar Invoice Items
        if (isset($invoiceDetails['invoiceItems']) && is_array($invoiceDetails['invoiceItems'])) {
            $this->processInvoiceItems($invoiceId, $invoiceDetails['invoiceItems']);
        }

        return $invoiceId;
    }

    /**
     * Procesa Invoice Items
     */
    private function processInvoiceItems(string $invoiceId, array $items): void
    {
        foreach ($items as $item) {
            $itemId = $item['_id'] ?? null;

            if (!$itemId) {
                continue;
            }

            InvoiceItem::updateOrCreate(
                ['item_id' => $itemId],
                [
                    'invoice_id' => $invoiceId,
                    'product_id' => $item['productId'] ?? null,
                    'price_id' => $item['priceId'] ?? null,
                    'currency' => $item['currency'] ?? '',
                    'name' => $item['name'] ?? '',
                    'qty' => $item['qty'] ?? 1,
                    'amount' => $item['amount'] ?? 0,
                    'description' => $item['description'] ?? null,
                    'tax_inclusive' => $item['taxInclusive'] ?? true,
                    'taxes' => $item['taxes'] ?? null
                ]
            );
        }
    }

    /**
     * Procesa datos de Workflow
     */
    private function processWorkflow(array $workflowData): string
    {
        $workflowId = $workflowData['id'] ?? null;

        if (!$workflowId) {
            throw new \Exception('Workflow ID es requerido');
        }

        Workflow::updateOrCreate(
            ['workflow_id' => $workflowId],
            [
                'name' => $workflowData['name'] ?? '',
                'trigger_data' => $workflowData['triggerData'] ?? null
            ]
        );

        return $workflowId;
    }

    /**
     * Obtiene todos los contactos
     */
    public function getContacts(): JsonResponse
    {
        try {
            $contacts = Contact::with(['location', 'invoice', 'workflow'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $contacts
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo contactos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene un contacto específico
     */
    public function getContact(string $contactId): JsonResponse
    {
        try {
            $contact = Contact::with(['location', 'invoice.invoiceItems', 'workflow'])
                ->where('contact_id', $contactId)
                ->first();

            if (!$contact) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contacto no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $contact
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo contacto: ' . $e->getMessage()
            ], 500);
        }
    }
}
