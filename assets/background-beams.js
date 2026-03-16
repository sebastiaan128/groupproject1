(function () {
    var BEAM_COUNT = 12;

    function isDark() {
        return document.documentElement.classList.contains('dark');
    }

    function getColors() {
        return isDark()
            ? ['#3b82f6', '#8b5cf6', '#06b6d4', '#a78bfa', '#ec4899', '#38bdf8']
            : ['#2563eb', '#7c3aed', '#0284c7', '#6d28d9', '#db2777', '#0369a1'];
    }

    function createBeam(canvasWidth, canvasHeight, randomY) {
        var colors = getColors();
        return {
            x: Math.random() * canvasWidth,
            y: randomY ? Math.random() * canvasHeight : -(50 + Math.random() * 150),
            speed: 2.5 + Math.random() * 4,
            width: 1.2 + Math.random() * 2,
            color: colors[Math.floor(Math.random() * colors.length)],
            length: 80 + Math.random() * 140,
            opacity: 0.4 + Math.random() * 0.5,
        };
    }

    function createExplosion(particles, x, color) {
        var count = 14 + Math.floor(Math.random() * 8);
        for (var i = 0; i < count; i++) {
            var angle = (Math.PI * 2 * i) / count + (Math.random() - 0.5) * 0.4;
            var speed = 1 + Math.random() * 3.5;
            particles.push({
                x: x,
                y: 0,
                vx: Math.cos(angle) * speed,
                vy: Math.sin(angle) * speed - 2.5,
                life: 1,
                decay: 0.02 + Math.random() * 0.025,
                size: 1.2 + Math.random() * 2.5,
                color: color,
            });
        }
    }

    function drawBeam(ctx, beam) {
        var gradient = ctx.createLinearGradient(beam.x, beam.y - beam.length, beam.x, beam.y);
        gradient.addColorStop(0, 'rgba(0,0,0,0)');
        gradient.addColorStop(0.6, hexToRgba(beam.color, 0.4));
        gradient.addColorStop(1, hexToRgba(beam.color, beam.opacity));

        ctx.save();

        ctx.beginPath();
        ctx.moveTo(beam.x, beam.y - beam.length);
        ctx.lineTo(beam.x, beam.y);
        ctx.strokeStyle = gradient;
        ctx.lineWidth = beam.width * 3;
        ctx.globalAlpha = beam.opacity * 0.25;
        ctx.shadowBlur = 18;
        ctx.shadowColor = beam.color;
        ctx.stroke();

        ctx.beginPath();
        ctx.moveTo(beam.x, beam.y - beam.length);
        ctx.lineTo(beam.x, beam.y);
        ctx.strokeStyle = gradient;
        ctx.lineWidth = beam.width;
        ctx.globalAlpha = beam.opacity;
        ctx.shadowBlur = 12;
        ctx.shadowColor = beam.color;
        ctx.stroke();

        ctx.beginPath();
        ctx.moveTo(beam.x, beam.y - beam.length * 0.4);
        ctx.lineTo(beam.x, beam.y);
        ctx.strokeStyle = 'rgba(255,255,255,0.8)';
        ctx.lineWidth = beam.width * 0.3;
        ctx.globalAlpha = beam.opacity * 0.7;
        ctx.shadowBlur = 4;
        ctx.shadowColor = '#ffffff';
        ctx.stroke();

        ctx.restore();
    }

    function hexToRgba(hex, alpha) {
        var r = parseInt(hex.slice(1, 3), 16);
        var g = parseInt(hex.slice(3, 5), 16);
        var b = parseInt(hex.slice(5, 7), 16);
        return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
    }

    function init(canvas) {
        var ctx = canvas.getContext('2d');
        var beams = [];
        var particles = [];

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        resize();
        window.addEventListener('resize', resize);

        for (var i = 0; i < BEAM_COUNT; i++) {
            beams.push(createBeam(canvas.width, canvas.height, true));
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (var i = 0; i < beams.length; i++) {
                var beam = beams[i];
                beam.y += beam.speed;
                drawBeam(ctx, beam);

                if (beam.y - beam.length > canvas.height) {
                    createExplosion(particles, beam.x, beam.color);
                    var colors = getColors();
                    beam.x = Math.random() * canvas.width;
                    beam.y = -(50 + Math.random() * 150);
                    beam.speed = 2.5 + Math.random() * 4;
                    beam.color = colors[Math.floor(Math.random() * colors.length)];
                    beam.opacity = 0.4 + Math.random() * 0.5;
                    beam.length = 80 + Math.random() * 140;
                }
            }

            for (var j = particles.length - 1; j >= 0; j--) {
                var p = particles[j];
                p.x += p.vx;
                p.y += p.vy;
                p.vy += 0.12;
                p.life -= p.decay;

                if (p.life <= 0) {
                    particles.splice(j, 1);
                    continue;
                }

                ctx.save();
                ctx.beginPath();
                ctx.arc(p.x, canvas.height + p.y, p.size, 0, Math.PI * 2);
                ctx.fillStyle = p.color;
                ctx.globalAlpha = p.life * 0.85;
                ctx.shadowBlur = 6;
                ctx.shadowColor = p.color;
                ctx.fill();
                ctx.restore();
            }

            requestAnimationFrame(animate);
        }

        animate();
    }

    document.addEventListener('DOMContentLoaded', function () {
        var canvas = document.getElementById('bg-beams');
        if (canvas) {
            init(canvas);
        }
    });
})();
