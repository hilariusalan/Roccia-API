<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Roccia Admin - Login</title>
</head>
<body>
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md py-14 px-11 bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-shadow duration-300 ease-in-out">
            <h2 class="text-3xl font-bold text-gray-800 mb-11 text-center">Masuk ke Akun Admin Roccia</h2>

            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="mb-4 text-red-600">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('auth.verif') }}" method="post">
                @csrf
                <label for="email-address-icon" class="block mb-2 text-sm font-medium text-gray-900">Email Anda</label>
                <div class="relative mb-6">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                            <path d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z"/>
                            <path d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z"/>
                        </svg>
                    </div>
                    <<input type="email" name="email" id="email-address-icon" ...>
                </div>
                
                <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200">
                    Lanjutkan
                </button>
            </form>
        </div>
    </div>
</body>
</html>