<?php
require_once __DIR__.'/../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

// Iniciar sesión
session_start();
// Configuración básica
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Obtener la ruta solicitada
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = rtrim($path, '/') ?: '/';

// Respuesta HTML mejorada con Tailwind CSS
function sendResponse($title, $content, $status = 200) {
    http_response_code($status);
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>'.$title.' | Sistema Biblioteca</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: "Montserrat", sans-serif;
            }
            .title-font {
                font-family: "Playfair Display", serif;
            }
            .fade-in {
                animation: fadeIn 0.8s ease-in-out;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .btn-hover {
                transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            }
            .btn-hover:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }
            .book-shadow {
                box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.15);
            }
            .carousel-item {
                transition: transform 0.6s ease-in-out;
            }
            .glow-text {
                text-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
            }
        </style>
    </head>
    <body class="bg-gray-50 antialiased">
        <div class="min-h-screen flex flex-col">
            <!-- Header -->
            <header class="bg-gradient-to-r from-blue-900 to-blue-700 text-white shadow-xl">
                <div class="container mx-auto px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <h1 class="title-font text-2xl font-bold tracking-tight glow-text">Biblioteca Digital</h1>
                        </div>
                        <nav class="hidden md:flex space-x-2">
                            <a href="/proyecto_unidad3_DWP/public/" class="px-4 py-2 rounded-lg hover:bg-blue-800/50 transition-all duration-300 flex items-center space-x-1">
                                <i class="fas fa-home text-blue-200"></i>
                                <span>Inicio</span>
                            </a>
                            <a href="/proyecto_unidad3_DWP/public/login" class="px-4 py-2 rounded-lg hover:bg-blue-800/50 transition-all duration-300 flex items-center space-x-1">
                                <i class="fas fa-sign-in-alt text-blue-200"></i>
                                <span>Login</span>
                            </a>
                            <a href="/proyecto_unidad3_DWP/public/register" class="px-4 py-2 rounded-lg hover:bg-blue-800/50 transition-all duration-300 flex items-center space-x-1">
                                <i class="fas fa-user-plus text-blue-200"></i>
                                <span>Registro</span>
                            </a>
                            <a href="#" class="px-4 py-2 rounded-lg hover:bg-blue-800/50 transition-all duration-300 flex items-center space-x-1">
                                <i class="fas fa-book text-blue-200"></i>
                                <span>Catálogo</span>
                            </a>
                        </nav>
                        <button class="md:hidden focus:outline-none p-2 rounded-lg hover:bg-blue-800/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-grow fade-in">
                '.$content.'
            </main>

            <!-- Footer -->
            <footer class="bg-gradient-to-r from-gray-900 to-gray-800 text-white py-8">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="mb-6 md:mb-0">
                            <h2 class="title-font text-xl font-semibold mb-2">Biblioteca Digital</h2>
                            <p class="text-gray-400 text-sm">Explora nuestro vasto catálogo de libros y recursos digitales.</p>
                        </div>
                        <div class="flex space-x-6">
                            <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                                <i class="fab fa-facebook-f text-lg"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                                <i class="fab fa-twitter text-lg"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                                <i class="fab fa-instagram text-lg"></i>
                            </a>
                        </div>
                    </div>
                    <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400 text-sm">
                        <p>© '.date('Y').' Biblioteca Digital. Todos los derechos reservados.</p>
                    </div>
                </div>
            </footer>
        </div>
        <script>
            // Simple carousel functionality
            document.addEventListener("DOMContentLoaded", function() {
                const carousel = document.querySelector(".carousel");
                if (carousel) {
                    const items = document.querySelectorAll(".carousel-item");
                    const totalItems = items.length;
                    let currentIndex = 0;
                    
                    function updateCarousel() {
                        items.forEach((item, index) => {
                            item.classList.remove("opacity-0", "scale-90", "z-10");
                            item.classList.add("opacity-0", "scale-90", "absolute");
                            
                            if (index === currentIndex) {
                                item.classList.remove("opacity-0", "scale-90");
                                item.classList.add("opacity-100", "scale-100", "relative", "z-20");
                            } else if (index === (currentIndex + 1) % totalItems) {
                                item.classList.add("opacity-70", "scale-95", "z-10");
                            }
                        });
                    }
                    
                    function nextSlide() {
                        currentIndex = (currentIndex + 1) % totalItems;
                        updateCarousel();
                    }
                    
                    // Auto-rotate every 5 seconds
                    setInterval(nextSlide, 5000);
                    updateCarousel();
                    
                    // Navigation buttons
                    document.querySelector(".carousel-next").addEventListener("click", nextSlide);
                    document.querySelector(".carousel-prev").addEventListener("click", function() {
                        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
                        updateCarousel();
                    });
                }
            });
        </script>
    </body>
    </html>';
    exit;
}

// Sistema de rutas básico
switch ($path) {
    case '/proyecto_unidad3_DWP/public/':
    case '/proyecto_unidad3_DWP/public':
        sendResponse('Bienvenido', '
            <div class="relative overflow-hidden">
                <!-- Hero Section -->
                <div class="bg-gradient-to-br from-blue-900 to-blue-600 text-white py-20">
                    <div class="container mx-auto px-6 text-center">
                        <h2 class="title-font text-4xl md:text-5xl font-bold mb-6 animate__animated animate__fadeInDown">Descubre el Mundo de los Libros</h2>
                        <p class="text-xl text-blue-100 max-w-2xl mx-auto mb-8 animate__animated animate__fadeIn animate__delay-1s">Explora nuestra colección de más de 10,000 títulos digitales disponibles para ti.</p>
                        <div class="flex flex-col sm:flex-row justify-center gap-4 animate__animated animate__fadeIn animate__delay-2s">
                            <a href="/proyecto_unidad3_DWP/public/login" class="btn-hover bg-white text-blue-800 hover:bg-blue-100 font-semibold py-3 px-8 rounded-full shadow-lg transform transition-all duration-300 inline-flex items-center justify-center">
                                <i class="fas fa-sign-in-alt mr-2"></i> Acceder
                            </a>
                            <a href="/proyecto_unidad3_DWP/public/register" class="btn-hover bg-transparent border-2 border-white text-white hover:bg-white hover:text-blue-800 font-semibold py-3 px-8 rounded-full shadow-lg transform transition-all duration-300 inline-flex items-center justify-center">
                                <i class="fas fa-user-plus mr-2"></i> Registrarse
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Carrusel de libros destacados -->
                <div class="container mx-auto px-6 py-16">
                    <h3 class="title-font text-3xl font-bold text-center text-gray-800 mb-12">Libros Destacados</h3>
                    
                    <div class="relative max-w-4xl mx-auto">
                        <div class="carousel">
                            <!-- Item 1 -->
                            <div class="carousel-item bg-white rounded-xl overflow-hidden book-shadow transition-all duration-500">
                                <div class="flex flex-col md:flex-row">
                                    <div class="md:w-1/3 bg-blue-50 flex items-center justify-center p-6">
                                        <div class="w-48 h-64 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg shadow-inner flex items-center justify-center">
                                            <i class="fas fa-book-open text-5xl text-blue-400 opacity-50"></i>
                                        </div>
                                    </div>
                                    <div class="md:w-2/3 p-8">
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full mb-2">Novedad</span>
                                        <h4 class="title-font text-2xl font-bold text-gray-800 mb-3">El Arte de la Programación</h4>
                                        <p class="text-gray-600 mb-4">Una guía completa para dominar los conceptos fundamentales de programación y algoritmos.</p>
                                        <div class="flex items-center text-yellow-400 mb-4">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                            <span class="text-gray-600 ml-2 text-sm">4.7 (128 reseñas)</span>
                                        </div>
                                        <button class="btn-hover bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg shadow transition duration-300">
                                            <i class="fas fa-bookmark mr-2"></i> Reservar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Item 2 -->
                            <div class="carousel-item bg-white rounded-xl overflow-hidden book-shadow transition-all duration-500">
                                <div class="flex flex-col md:flex-row">
                                    <div class="md:w-1/3 bg-purple-50 flex items-center justify-center p-6">
                                        <div class="w-48 h-64 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg shadow-inner flex items-center justify-center">
                                            <i class="fas fa-atom text-5xl text-purple-400 opacity-50"></i>
                                        </div>
                                    </div>
                                    <div class="md:w-2/3 p-8">
                                        <span class="inline-block bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full mb-2">Ciencia</span>
                                        <h4 class="title-font text-2xl font-bold text-gray-800 mb-3">Breve Historia del Tiempo</h4>
                                        <p class="text-gray-600 mb-4">Un viaje fascinante a través de los misterios del universo y las leyes que lo gobiernan.</p>
                                        <div class="flex items-center text-yellow-400 mb-4">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <span class="text-gray-600 ml-2 text-sm">5.0 (256 reseñas)</span>
                                        </div>
                                        <button class="btn-hover bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-6 rounded-lg shadow transition duration-300">
                                            <i class="fas fa-bookmark mr-2"></i> Reservar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Item 3 -->
                            <div class="carousel-item bg-white rounded-xl overflow-hidden book-shadow transition-all duration-500">
                                <div class="flex flex-col md:flex-row">
                                    <div class="md:w-1/3 bg-green-50 flex items-center justify-center p-6">
                                        <div class="w-48 h-64 bg-gradient-to-br from-green-100 to-green-200 rounded-lg shadow-inner flex items-center justify-center">
                                            <i class="fas fa-leaf text-5xl text-green-400 opacity-50"></i>
                                        </div>
                                    </div>
                                    <div class="md:w-2/3 p-8">
                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full mb-2">Naturaleza</span>
                                        <h4 class="title-font text-2xl font-bold text-gray-800 mb-3">El Bosque Silencioso</h4>
                                        <p class="text-gray-600 mb-4">Una exploración profunda de los ecosistemas forestales y su importancia para nuestro planeta.</p>
                                        <div class="flex items-center text-yellow-400 mb-4">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <span class="text-gray-600 ml-2 text-sm">4.0 (84 reseñas)</span>
                                        </div>
                                        <button class="btn-hover bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg shadow transition duration-300">
                                            <i class="fas fa-bookmark mr-2"></i> Reservar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Carousel Controls -->
                        <button class="carousel-prev absolute left-0 top-1/2 -translate-y-1/2 -translate-x-12 bg-white p-3 rounded-full shadow-lg text-blue-600 hover:text-blue-800 focus:outline-none z-30">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="carousel-next absolute right-0 top-1/2 -translate-y-1/2 translate-x-12 bg-white p-3 rounded-full shadow-lg text-blue-600 hover:text-blue-800 focus:outline-none z-30">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Features Section -->
                <div class="bg-gray-100 py-16">
                    <div class="container mx-auto px-6">
                        <h3 class="title-font text-3xl font-bold text-center text-gray-800 mb-12">Nuestros Servicios</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <!-- Feature 1 -->
                            <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 text-center">
                                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-book text-2xl text-blue-600"></i>
                                </div>
                                <h4 class="title-font text-xl font-semibold text-gray-800 mb-3">Amplio Catálogo</h4>
                                <p class="text-gray-600">Accede a más de 10,000 títulos en diferentes áreas del conocimiento.</p>
                            </div>
                            
                            <!-- Feature 2 -->
                            <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 text-center">
                                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-laptop text-2xl text-purple-600"></i>
                                </div>
                                <h4 class="title-font text-xl font-semibold text-gray-800 mb-3">Acceso Digital</h4>
                                <p class="text-gray-600">Lee desde cualquier dispositivo, en cualquier momento y lugar.</p>
                            </div>
                            
                            <!-- Feature 3 -->
                            <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 text-center">
                                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-headset text-2xl text-green-600"></i>
                                </div>
                                <h4 class="title-font text-xl font-semibold text-gray-800 mb-3">Soporte 24/7</h4>
                                <p class="text-gray-600">Nuestro equipo está disponible para ayudarte cuando lo necesites.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ');
        break;
        
    case '/proyecto_unidad3_DWP/public/login':
        sendResponse('Iniciar Sesión', '
            <div class="bg-gradient-to-br from-blue-50 to-gray-50 py-16 min-h-full">
                <div class="container mx-auto px-6">
                    <div class="max-w-md mx-auto bg-white rounded-xl shadow-2xl overflow-hidden animate__animated animate__fadeIn">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-400 p-6 text-white text-center">
                            <h2 class="title-font text-2xl font-bold">Iniciar Sesión</h2>
                            <p class="text-blue-100">Accede a tu cuenta para continuar</p>
                        </div>
                        
                        <form method="post" class="p-8 space-y-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" id="email" name="email" required 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                        placeholder="tu@email.com">
                                </div>
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="password" name="password" required 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                        placeholder="••••••••">
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="remember-me" class="ml-2 block text-sm text-gray-700">Recordarme</label>
                                </div>
                                <div class="text-sm">
                                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">¿Olvidaste tu contraseña?</a>
                                </div>
                            </div>
                            
                            <div>
                                <button type="submit" 
                                    class="btn-hover w-full bg-gradient-to-r from-blue-600 to-blue-400 hover:from-blue-700 hover:to-blue-500 text-white font-semibold py-3 px-4 rounded-lg shadow-lg transition duration-300 flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Ingresar
                                </button>
                            </div>
                        </form>
                        
                        <div class="px-8 pb-6 text-center">
                            <p class="text-gray-600 text-sm">¿No tienes una cuenta? <a href="/proyecto_unidad3_DWP/public/register" class="text-blue-600 hover:text-blue-800 font-medium">Regístrate</a></p>
                        </div>
                    </div>
                </div>
            </div>
        ');
        break;
        
    case '/proyecto_unidad3_DWP/public/register':
        sendResponse('Registro', '
            <div class="bg-gradient-to-br from-blue-50 to-gray-50 py-16 min-h-full">
                <div class="container mx-auto px-6">
                    <div class="max-w-md mx-auto bg-white rounded-xl shadow-2xl overflow-hidden animate__animated animate__fadeIn">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-400 p-6 text-white text-center">
                            <h2 class="title-font text-2xl font-bold">Crear Cuenta</h2>
                            <p class="text-blue-100">Regístrate para acceder a todos nuestros recursos</p>
                        </div>
                        
                        <form method="post" class="p-8 space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" id="name" name="name" required 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                        placeholder="Tu nombre completo">
                                </div>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" id="email" name="email" required 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                        placeholder="tu@email.com">
                                </div>
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="password" name="password" required 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                        placeholder="••••••••">
                                </div>
                            </div>
                            
                            <div>
                                <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="confirm-password" name="confirm-password" required 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                                        placeholder="••••••••">
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                                <label for="terms" class="ml-2 block text-sm text-gray-700">
                                    Acepto los <a href="#" class="text-blue-600 hover:text-blue-800">Términos y Condiciones</a>
                                </label>
                            </div>
                            
                            <div>
                                <button type="submit" 
                                    class="btn-hover w-full bg-gradient-to-r from-blue-600 to-blue-400 hover:from-blue-700 hover:to-blue-500 text-white font-semibold py-3 px-4 rounded-lg shadow-lg transition duration-300 flex items-center justify-center">
                                    <i class="fas fa-user-plus mr-2"></i> Registrarse
                                </button>
                            </div>
                        </form>
                        
                        <div class="px-8 pb-6 text-center">
                            <p class="text-gray-600 text-sm">¿Ya tienes una cuenta? <a href="/proyecto_unidad3_DWP/public/login" class="text-blue-600 hover:text-blue-800 font-medium">Inicia sesión</a></p>
                        </div>
                    </div>
                </div>
            </div>
        ');
        break;
        
    default:
        sendResponse('Página no encontrada', '
            <div class="bg-gradient-to-br from-gray-50 to-blue-50 flex items-center justify-center min-h-screen py-12">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-md w-full animate__animated animate__fadeIn">
                    <div class="bg-gradient-to-r from-red-600 to-red-400 p-6 text-white text-center">
                        <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                        <h2 class="title-font text-2xl font-bold">Error 404</h2>
                        <p class="text-red-100">Página no encontrada</p>
                    </div>
                    
                    <div class="p-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-red-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="title-font text-xl font-semibold text-gray-800 mb-2">Lo sentimos</h3>
                        <p class="text-gray-600 mb-6">La página que estás buscando no existe o ha sido movida.</p>
                        <a href="/proyecto_unidad3_DWP/public/" 
                            class="btn-hover inline-block bg-gradient-to-r from-blue-600 to-blue-400 hover:from-blue-700 hover:to-blue-500 text-white font-medium py-2 px-6 rounded-lg shadow-lg transition duration-300">
                            <i class="fas fa-home mr-2"></i> Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        ', 404);
}