<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Exceptions\NotNotifyPayeeException;
use App\Interfaces\ExternalNotifyInterface;
use App\Interfaces\UserInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyPayee implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private UserInterface $payee
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(ExternalNotifyInterface $externalNotify): void
    {
        if (!$externalNotify->consult($this->payee)) {
            $this->fail(new NotNotifyPayeeException());
        }
    }
}
