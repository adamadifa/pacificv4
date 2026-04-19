<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    /**
     * Subscribe a user to push notifications.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);

        $endpoint = $request->endpoint;
        $key = $request->keys['p256dh'];
        $token = $request->keys['auth'];
        $contentEncoding = $request->content_encoding ?? 'aesgcm';

        $request->user()->updatePushSubscription($endpoint, $key, $token, $contentEncoding);

        return response()->json([
            'success' => true,
            'message' => 'Subscription updated successfully.'
        ]);
    }

    /**
     * Unsubscribe a user from push notifications.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsubscribe(Request $request)
    {
        $this->validate($request, [
            'endpoint' => 'required'
        ]);

        $request->user()->deletePushSubscription($request->endpoint);

        return response()->json([
            'success' => true,
            'message' => 'Subscription deleted successfully.'
        ]);
    }
}
