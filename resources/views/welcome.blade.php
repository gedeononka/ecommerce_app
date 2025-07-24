<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>E-Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
      href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
      rel="stylesheet"
    />
</head>
<body class="bg-gray-50 font-sans text-black">
  <!-- Navigation Bar -->
  <nav class="bg-white shadow-md border-b" style="border-color: #008000;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <div class="flex-shrink-0 flex items-center">
              <img src="{{ asset('images/logo.png') }}" alt="E-Shop Logo" class="h-10">
          </div>
        </div>
        <div class="hidden sm:ml-6 sm:flex sm:items-center">
          @auth
            <a href="{{ route('dashboard') }}" class="ml-4 text-black hover:underline" style="color: #191919;">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" class="ml-4">
              @csrf
              <button type="submit" class="text-black hover:underline" style="color: #191919;">Logout</button>
            </form>
          @else
            <a href="{{ route('login') }}" class="ml-4 text-black hover:underline" style="color: #191919;">Login</a>
            <a href="{{ route('register') }}" class="ml-4 text-black hover:underline" style="color: #191919;">Register</a>
          @endauth
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <header
    class="text-white"
    style="background: linear-gradient(to right, #008000, #006400);"
  >
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 text-center">
      <h1 class="text-4xl font-extrabold sm:text-5xl md:text-6xl">
        Welcome to E-Shop
      </h1>
      <p class="mt-3 max-w-md mx-auto text-base sm:text-lg md:mt-5 md:text-xl">
        Your one-stop shop for all your needs. Get started now!
      </p>
      <div class="mt-5 sm:mt-8 flex justify-center">
        <a
          href="{{ route('dashboard') }}"
          class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white shadow-lg"
          style="background-color: #008000;"
          >Go to Dashboard</a
        >
      </div>
    </div>
  </header>
  
  <!-- Footer -->
  <footer class="bg-gray-100 text-black border-t border-gray-200">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <h3 class="text-lg font-medium" style="color: #191919;">E-Shop</h3>
          <p class="mt-2 text-sm text-black">Your one-stop shop for all your needs.</p>
        </div>
        <div>
          <h3 class="text-lg font-medium" style="color: #191919;">Quick Links</h3>
          <ul class="mt-2 space-y-2">
            <li>
              <a
                href="{{ route('dashboard') }}"
                class="text-sm hover:underline"
                style="color: #191919;"
                >Dashboard</a
              >
            </li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-medium" style="color: #191919;">Contact Us</h3>
          <p class="mt-2 text-sm text-black">Email: support@eshop.com</p>
          <p class="mt-1 text-sm text-black">Phone: (123) 456-7890</p>
        </div>
      </div>
      <div class="mt-8 border-t border-gray-300 pt-8 text-center">
        <p class="text-sm text-black">© {{ date('Y') }} E-Shop. All rights reserved.</p>
      </div>
    </div>
  </footer>
</body>
</html>
