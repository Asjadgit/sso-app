<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-gray-100">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
        <div class="p-4 text-xl font-bold text-center text-gray-700">Dashboard</div>
        <nav class="mt-4">
            <a href="#" class="block px-4 py-2 text-gray-600 hover:bg-blue-500 hover:text-white">🏠 Home</a>
            <a href="#" class="block px-4 py-2 text-gray-600 hover:bg-blue-500 hover:text-white">📊 Reports</a>
            <a href="#" class="block px-4 py-2 text-gray-600 hover:bg-blue-500 hover:text-white">⚙️ Settings</a>
            <a href="#" class="block px-4 py-2 text-gray-600 hover:bg-blue-500 hover:text-white">🔓 Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <header class="flex items-center justify-between p-4 bg-white shadow-md">
            <h2 class="text-xl font-semibold text-gray-700">Welcome Back, {{ Auth::user()->name ?? 'User' }}!</h2>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">Logout</button>
            </form>
        </header>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 gap-6 mt-6 md:grid-cols-2 lg:grid-cols-3">
            <div class="p-6 bg-white border rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">📈 Sales</h3>
                <p class="mt-2 text-2xl font-bold text-blue-500">$12,345</p>
            </div>
            <div class="p-6 bg-white border rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">👥 Users</h3>
                <p class="mt-2 text-2xl font-bold text-blue-500">1,234</p>
            </div>
            <div class="p-6 bg-white border rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">📦 Orders</h3>
                <p class="mt-2 text-2xl font-bold text-blue-500">567</p>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="mt-6 bg-white rounded-lg shadow-md">
            <div class="p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-700">📜 Recent Activity</h3>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="p-4">User</th>
                        <th class="p-4">Action</th>
                        <th class="p-4">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="p-4">John Doe</td>
                        <td class="p-4 text-blue-500">Created an Order</td>
                        <td class="p-4">March 19, 2025</td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-4">Jane Smith</td>
                        <td class="p-4 text-green-500">Updated Profile</td>
                        <td class="p-4">March 18, 2025</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
