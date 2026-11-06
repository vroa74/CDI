{{-- Adaptado de noise.tpl.html, error-pages de tarampampam.
Idea visual original: https://codepen.io/moklick
MIT License

Copyright (c) <https://github.com/tarampampam>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title>@yield('code') · @yield('title')</title>
    <style>
        * { box-sizing: border-box; }
        html { background: #111; color: #f3f4f6; font-family: Arial, sans-serif; }
        body { margin: 0; min-height: 100vh; min-height: 100svh; }
        #canvas, .frame { position: fixed; inset: 0; width: 100%; height: 100%; pointer-events: none; }
        #canvas { z-index: 0; opacity: .38; }
        .frame { z-index: 1; overflow: clip; background: radial-gradient(ellipse at center, transparent, rgba(0,0,0,.85)); }
        .frame div { position: absolute; inset-inline: 0; top: -25%; height: 20%; background: rgba(0,0,0,.12); animation: horizontalLine 12s linear infinite; }
        .frame div:nth-child(2) { animation-delay: 4s; }
        .frame div:nth-child(3) { animation-delay: 8s; }
        @keyframes horizontalLine { from { top: -25%; } to { top: 100%; } }
        main { position: relative; z-index: 2; display: grid; place-items: center; min-height: 100vh; min-height: 100svh; padding: 32px 20px; }
        .content { width: 100%; max-width: 760px; min-width: 0; text-align: center; overflow-wrap: anywhere; }
        .code { margin: 0; font-size: clamp(5rem, 26vw, 16rem); line-height: 1; color: #e5e7eb; text-shadow: 0 0 15px #000; }
        h2 { font-size: clamp(1.4rem, 5vw, 2.5rem); line-height: 1.2; margin: 24px 0 16px; }
        p { font-size: 1.05rem; line-height: 1.6; margin: 0; }
        nav { display: flex; flex-wrap: wrap; justify-content: center; gap: 16px; margin-top: 32px; }
        a { display: inline-flex; align-items: center; justify-content: center; min-height: 48px; padding: 12px 24px; border-radius: 8px; background: #4f46e5; color: #fff; text-decoration: none; font-weight: bold; max-width: 100%; }
        a:hover { background: #4338ca; }
        a:focus-visible { outline: 3px solid white; outline-offset: 4px; }
        @media (prefers-reduced-motion: reduce) { .frame div { animation: none; } }
    </style>
</head>
<body>
    <canvas id="canvas" aria-hidden="true"></canvas>
    <div class="frame" aria-hidden="true"><div></div><div></div><div></div></div>
    <main>
        <section class="content" aria-labelledby="error-title">
            <h1 class="code" aria-label="Error @yield('code')">@yield('code')</h1>
            <h2 id="error-title">@yield('message')</h2>
            <p>@yield('description')</p>
            <nav aria-label="Opciones para continuar">
                @auth('web')
                    <a href="{{ route('dashboard') }}">Volver al inicio</a>
                @else
                    <a href="{{ url('/') }}">Volver al inicio</a>
                @endauth
            </nav>
        </section>
    </main>
    <script>
        (() => {
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');
            if (!context) return;
            const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
            let timer;
            const resize = () => {
                canvas.width = Math.min(window.innerWidth, 800);
                canvas.height = Math.min(window.innerHeight, 600);
            };
            const draw = () => {
                const image = context.createImageData(canvas.width, canvas.height);
                for (let i = 0; i < image.data.length; i += 4) {
                    const color = Math.floor(Math.random() * 205) + 50;
                    image.data[i] = image.data[i + 1] = image.data[i + 2] = color;
                    image.data[i + 3] = 255;
                }
                context.putImageData(image, 0, 0);
            };
            const update = () => {
                window.clearInterval(timer);
                timer = undefined;
                if (!document.hidden) {
                    draw();
                    if (!motion.matches) timer = window.setInterval(draw, 90);
                }
            };
            resize();
            update();
            window.addEventListener('resize', () => { resize(); update(); });
            document.addEventListener('visibilitychange', update);
            motion.addEventListener('change', update);
        })();
    </script>
</body>
</html>
