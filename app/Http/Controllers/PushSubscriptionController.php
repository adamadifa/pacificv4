<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NotificationChannels\WebPush\PushSubscription;

class PushSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = PushSubscription::query();
        $query->join('hrd_karyawan', 'push_subscriptions.subscribable_id', '=', 'hrd_karyawan.nik');
        $query->where('push_subscriptions.subscribable_type', 'App\Models\Karyawan');
        $query->select('push_subscriptions.*', 'hrd_karyawan.nama_karyawan as user_name', 'hrd_karyawan.nik');

        if (!empty($request->user_name)) {
            $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->user_name . '%');
        }

        $query->orderBy('push_subscriptions.created_at', 'desc');
        $subscriptions = $query->paginate(20);
        $subscriptions->appends($request->all());

        return view('utilities.push_subscriptions.index', compact('subscriptions'));
    }

    public function destroy($id)
    {
        try {
            $subscription = PushSubscription::findOrFail($id);
            $subscription->delete();
            return redirect()->back()->with('success', 'Subscription deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
