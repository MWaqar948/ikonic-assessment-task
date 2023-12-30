<?php

namespace App\Services;

use App\Exceptions\AffiliateCreateException;
use App\Mail\AffiliateCreated;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;

class AffiliateService
{
    public function __construct(
        protected ApiService $apiService
    ) {}

    /**
     * Create a new affiliate for the merchant with the given commission rate.
     *
     * @param  Merchant $merchant
     * @param  string $email
     * @param  string $name
     * @param  float $commissionRate
     * @return Affiliate
     */
    public function register(Merchant $merchant, string $email, string $name, float $commissionRate): Affiliate
    {
        // TODO: Completed this method
        try
        {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'type' => User::TYPE_AFFILIATE
            ]);
            
            $affiliate = Affiliate::create([
                'discount_code' => $this->apiService->createDiscountCode($merchant)['code'],
                'commission_rate' => $commissionRate,
                'merchant_id' => $merchant->id,
                'user_id' => $user->id
            ]);
            
            Mail::to($user->email)->send(new AffiliateCreated($affiliate));
            return $affiliate;

        }catch(Exception $e){
            throw new AffiliateCreateException();
        }
    }
}
