<?php

namespace App\Jobs;

use App\Models\Recipient;
use App\Models\Transaction;
use App\Models\User;
use App\Services\WiseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessWiseTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    //stake/data holder -- Database serialize 
    public $userId;
    public $recipientId;
    public $transactionId;

    public function __construct($userId, $recipientId, $transactionId)
    {
        $this->userId = $userId;
        $this->recipientId = $recipientId;
        $this->transactionId = $transactionId;
    }

    public function handle(WiseService $wiseService): void
    {
        $user = User::find($this->userId);
        $recipient = Recipient::with('attributes', 'user', 'countryCurrency.currency', 'countryCurrency.country')->find($this->recipientId);
        $transaction = Transaction::find($this->transactionId);
        $attrs = $recipient->attributes->pluck('value', 'key')->toArray();

        if (!$user || !$recipient || !$transaction) {
            return;
        }
        // Log::info("WISE Job started", ['transaction_id' => $transaction->id]);

        $transaction->update([
            'status' => 'process'
        ]);

        try {

            if (!$user->wise_provider_id) {
                $wiseUser = $wiseService->connectUser($user);
                $user->update([
                    'wise_provider_id' => $wiseUser['id']
                ]);
            }

            if (!$recipient->wise_recipient_id) {
                $wiseRecipient = $wiseService->createRecipient($recipient, $attrs);
                // $recipient->update([
                //     'wise_recipient_id' => $wiseRecipient['id']
                // ]);
                $recipient->refresh();
            }

            $quote = $wiseService->createQuote(
                $user,
                $recipient,
                $transaction->amount
            );

            $transaction->update([
                'wise_quote_id' => $quote['id']
            ]);

            $transfer = $wiseService->createTransfer($quote);

            $transaction->update([
                'wise_transfer_id' => $transfer['id'],
                'wise_status'      => $transfer['status'] ?? null,
                'status'           => 'complete'
            ]);
            Log::info("WISE Job completed", ['transaction_id' => $transaction->id]);
        } catch (\Throwable $e) {

            $transaction->update([
                'status'     => 'failed',
                'wise_error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
