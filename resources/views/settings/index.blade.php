<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-6">System Settings</h2>

                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- General Settings -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">General Settings</h3>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Site Name -->
                            <div>
                                <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                                <input type="text" name="site_name" id="site_name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    value="{{ $settings['site_name'] ?? config('app.name') }}">
                            </div>

                            <!-- Contact Email -->
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                                <input type="email" name="contact_email" id="contact_email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    value="{{ $settings['contact_email'] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <!-- Order Settings -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Settings</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Minimum Order Amount -->
                            <div>
                                <label for="min_order_amount" class="block text-sm font-medium text-gray-700">Minimum Order Amount</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="min_order_amount" id="min_order_amount"
                                        class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        value="{{ $settings['min_order_amount'] ?? '0' }}"
                                        step="0.01">
                                </div>
                            </div>

                            <!-- Tax Rate -->
                            <div>
                                <label for="tax_rate" class="block text-sm font-medium text-gray-700">Tax Rate (%)</label>
                                <input type="number" name="tax_rate" id="tax_rate"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    value="{{ $settings['tax_rate'] ?? '0' }}"
                                    step="0.01">
                            </div>
                        </div>
                    </div>

                    <!-- Notification Settings -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Notification Settings</h3>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="order_confirmation_email" id="order_confirmation_email"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    {{ ($settings['order_confirmation_email'] ?? true) ? 'checked' : '' }}>
                                <label for="order_confirmation_email" class="ml-2 block text-sm text-gray-900">
                                    Send order confirmation emails
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="inventory_alerts" id="inventory_alerts"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    {{ ($settings['inventory_alerts'] ?? true) ? 'checked' : '' }}>
                                <label for="inventory_alerts" class="ml-2 block text-sm text-gray-900">
                                    Enable low inventory alerts
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>