<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Roccia Admin - Verification</title>
</head>
<body>
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md py-14 px-11 bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-shadow duration-300 ease-in-out">
            <h2 class="text-3xl font-bold text-gray-800 mb-11 text-center">Masukkan kode OTP yang telah diberikan</h2>
            <form action="{{ route('admin') }}" method="get">
                @csrf
                <label for="email-address-icon" class="block mb-2 text-sm font-medium text-gray-900">Kode OTP</label>
                <input type="text" id="repeat-password" class="mb-6 shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                
                <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</body>
</html>