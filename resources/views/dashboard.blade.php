<x-app-layout>
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-md h-screen p-4 flex flex-col justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-6">Admin Dashboard</h2>
                <ul>
                    <li class="mb-4">
                        <a class="flex items-center text-gray-700 hover:text-blue-500" href="#">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="mb-4">
                        <a class="flex items-center text-gray-700 hover:text-blue-500" href="#">
                            <i class="fas fa-tshirt mr-2"></i>
                            Designs
                        </a>
                    </li>
                    <li class="mb-4">
                        <a class="flex items-center text-gray-700 hover:text-blue-500" href="#">
                            <i class="fas fa-plus mr-2"></i>
                            Add New Design
                        </a>
                    </li>
                    <li class="mb-4">
                        <a class="flex items-center text-gray-700 hover:text-blue-500" href="#">
                            <i class="fas fa-edit mr-2"></i>
                            Manage Designs
                        </a>
                    </li>
                    <li class="mb-4">
                        <a class="flex items-center text-gray-700 hover:text-blue-500" href="#">
                            <i class="fas fa-tags mr-2"></i>
                            Categories
                        </a>
                    </li>
                    <li class="mb-4">
                        <a class="flex items-center text-gray-700 hover:text-blue-500" href="#">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Orders
                        </a>
                    </li>
                    <li class="mb-4">
                        <a class="flex items-center text-gray-700 hover:text-blue-500" href="#">
                            <i class="fas fa-history mr-2"></i>
                            Order History
                        </a>
                    </li>
                    <li class="mb-4">
                        <a class="flex items-center text-gray-700 hover:text-blue-500" href="#">
                            <i class="fas fa-search mr-2"></i>
                            Track Orders
                        </a>
                    </li>
                    <li class="mb-4">
                        <a class="flex items-center text-gray-700 hover:text-blue-500" href="#">
                            <i class="fas fa-users mr-2"></i>
                            Users
                        </a>
                    </li>
                    <li class="mb-4">
                        <a class="flex items-center text-gray-700 hover:text-blue-500" href="#">
                            <i class="fas fa-chart-line mr-2"></i>
                            Reports
                        </a>
                    </li>
                    <li class="mb-4">
                        <a class="flex items-center text-gray-700 hover:text-blue-500" href="#">
                            <i class="fas fa-cog mr-2"></i>
                            Settings
                        </a>
                    </li>
                </ul>
            </div>
            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-red-600 flex items-center w-full">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Log Out
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Welcome to the Admin Dashboard</h1>
            
            <!-- Admin Actions Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Add New Design Button -->
                <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Add New Design</h2>
                        <p class="text-gray-600">Launch a form to add a new t-shirt design.</p>
                    </div>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600 flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Add
                    </button>
                </div>

                <!-- View All Designs Button -->
                <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">View All Designs</h2>
                        <p class="text-gray-600">See a comprehensive list of all t-shirt designs.</p>
                    </div>
                    <button class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-green-600 flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        View
                    </button>
                </div>

                <!-- Manage Categories Button -->
                <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Manage Categories</h2>
                        <p class="text-gray-600">Create, delete, or edit categories for t-shirt designs.</p>
                    </div>
                    <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-yellow-600 flex items-center">
                        <i class="fas fa-tags mr-2"></i>
                        Manage
                    </button>
                </div>

                <!-- View Orders Button -->
                <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">View Orders</h2>
                        <p class="text-gray-600">See all customer orders and update order statuses.</p>
                    </div>
                    <button class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-red-600 flex items-center">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        View
                    </button>
                </div>

                <!-- Manage Users Button -->
                <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Manage Users</h2>
                        <p class="text-gray-600">Manage customer accounts and view past orders.</p>
                    </div>
                    <button class="bg-purple-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-purple-600 flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        Manage
                    </button>
                </div>
            </div>

            <!-- Design Management Section -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h2 class="text-2xl font-semibold mb-4">Design Management</h2>
                <div class="flex flex-wrap gap-4 mb-4">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600">
                        Manage Inventory
                    </button>
                </div>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Design</th>
                            <th class="py-2 px-4 border-b">Title</th>
                            <th class="py-2 px-4 border-b">Price</th>
                            <th class="py-2 px-4 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b">
                                <img src="https://storage.googleapis.com/a1aa/image/sdtsHM8eT3CziaY6oRtp_4Sjb7XdREl1JGtqxy5tgDw.jpg" 
                                     alt="Thumbnail of a t-shirt design with a cool graphic" 
                                     class="w-12 h-12"
                                />
                            </td>
                            <td class="py-2 px-4 border-b">Cool T-Shirt</td>
                            <td class="py-2 px-4 border-b">$20.00</td>
                            <td class="py-2 px-4 border-b">
                                <button class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 mr-2">
                                    Edit
                                </button>
                                <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 mr-2">
                                    Delete
                                </button>
                                <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                    View
                                </button>
                            </td>
                        </tr>
                        <!-- Add more design rows as needed -->
                    </tbody>
                </table>
            </div>

            <!-- Order Management Section -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h2 class="text-2xl font-semibold mb-4">Order Management</h2>
                <div class="flex flex-wrap gap-4 mb-4">
                    <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-yellow-600">
                        Pending Orders
                    </button>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-600">
                        Shipped Orders
                    </button>
                    <button class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-green-600">
                        Completed Orders
                    </button>
                </div>
                <div class="mb-4">
                    <input type="text" placeholder="Search Orders" class="w-full p-2 border rounded-lg"/>
                </div>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Order ID</th>
                            <th class="py-2 px-4 border-b">Customer Name</th>
                            <th class="py-2 px-4 border-b">Design</th>
                            <th class="py-2 px-4 border-b">Status</th>
                            <th class="py-2 px-4 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b">#12345</td>
                            <td class="py-2 px-4 border-b">John Doe</td>
                            <td class="py-2 px-4 border-b">Cool T-Shirt</td>
                            <td class="py-2 px-4 border-b">Pending</td>
                            <td class="py-2 px-4 border-b">
                                <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 mr-2">
                                    View Details
                                </button>
                            </td>
                        </tr>
                        <!-- Add more order rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</x-app-layout>
