<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-posta Doğrulama Başarılı - UniNotes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                E-posta Adresiniz Doğrulandı!
            </h1>
            
            <p class="text-gray-600 mb-8">
                Hesabınız başarıyla aktifleştirildi. Artık UniNotes'u kullanmaya başlayabilirsiniz.
            </p>
            
            <div class="space-y-4">
                <a href="http://localhost:5173/login" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Giriş Yap
                </a>
                
                <a href="http://localhost:5173" 
                   class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Ana Sayfaya Dön
                </a>
            </div>
        </div>
        
        <div class="text-center mt-6 text-gray-500 text-sm">
            &copy; {{ date('Y') }} UniNotes. Tüm hakları saklıdır.
        </div>
    </div>
</body>
</html>
