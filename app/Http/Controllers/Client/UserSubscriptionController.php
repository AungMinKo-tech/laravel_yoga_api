<?php

namespace App\Http\Controllers\Client;


use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;
use App\Models\SubscriptionUser;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Client\UserSubscriptionResource;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Http\Resources\Dashboard\AdminSubscriptionResource;

class UserSubscriptionController extends Controller
{
    use ApiResponse;

    /**
     * GET /users/{id}/subscriptions
     * List user's subscription
     */
    public function index($id) {

        $subscription = SubscriptionUser::where('user_id', $id)->get();

        if(!$subscription) {
            return $this->errorResponse('Subscription not found', 404);
        }

        return $this->successResponse('Your subscription ', UserSubscriptionResource::collection($subscription), 200);
    }

    /**
     * POST /users/{id}/subscriptions
     * Create user's subscription
     */
    public function store(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'phoneNo' => 'required',
            'payslipImage' => 'required|mimes:png,jpg,jpeg',
            'transanctionId' => 'required',
            'subscriptionId' => 'required|integer|exists:subscriptions,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $subscription = Subscription::find($request->subscriptionId);

        if (!$subscription) {
            return $this->errorResponse('Subscription not found!', 404);
        }

        try {
            $paymentData = [
                'user_id' => $id,
                'payment_method_id' => $request->paymentMethodId,
                'subscription_id' => $request->subscriptionId,
                'ph_no' => $request->phNo,
                'transaction_id' => $request->transactionId,
            ];

            if ($request->hasFile('payslipImage')) {
                $uploadedFile = Cloudinary::upload($request->file('payslipImage')->getRealPath(), ['folder' => 'payslip_image'])->getSecurePath();
                $paymentData['payslip_image_url'] = $uploadedFile['secure_url'];
                $paymentData['payslip_image_public_id'] = $uploadedFile['public_id'];
            }

            PaymentHistory::create($paymentData);

            $user = SubscriptionUser::create([
                'user_id' => $id,
                'subscription_id' => $request->subscriptionId,
                'status' => 'pending'
            ]);

            return $this->successResponse(
                'Payment submitted.Waiting for admin approval.',
                [
                    "payment" => new UserSubscriptionResource($paymentData),
                    "subscription" => new AdminSubscriptionResource($user)
                ],
                201
            );

        } catch (\Exception $e) {
            return $this->errorResponse('Subscription attach failed: ' . $e->getMessage(), 500);
        }
    }
}
