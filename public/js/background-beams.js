(function () {

    var STAR_COUNT     = 180;
    var MAX_SHOOTING   = 5;
    var SPAWN_INTERVAL = 2000;
    var MAX_SPARKLES   = 18;
    var SPARKLE_SPAWN  = 400;

    function rnd(min, max) { return min + Math.random() * (max - min); }

    function isDark() {
        return document.documentElement.classList.contains('dark');
    }

    function hexAlpha(hex, a) {
        var r = parseInt(hex.slice(1, 3), 16);
        var g = parseInt(hex.slice(3, 5), 16);
        var b = parseInt(hex.slice(5, 7), 16);
        return 'rgba(' + r + ',' + g + ',' + b + ',' + a + ')';
    }


    function makeStar(w, h) {
        return {
            x:     rnd(0, w),
            y:     rnd(0, h),
            r:     rnd(0.4, 1.6),
            base:  rnd(0.2, 0.9),
            alpha: rnd(0.2, 0.9),
            delta: rnd(0.003, 0.012) * (Math.random() < 0.5 ? 1 : -1),
        };
    }

    var COLORS = ['#a78bfa', '#60a5fa', '#f472b6', '#34d399', '#fbbf24', '#e0e7ff'];

    function makeShooting(w, h) {
        var fromTop = Math.random() < 0.6;
        var sx = fromTop ? rnd(0, w) : rnd(w * 0.6, w);
        var sy = fromTop ? rnd(0, h * 0.3) : rnd(0, h * 0.4);

        var angleDeg = rnd(200, 250);
        var angle    = angleDeg * Math.PI / 180;
        var speed    = rnd(10, 20);

        return {
            x:     sx,
            y:     sy,
            vx:    Math.cos(angle) * speed,
            vy:    Math.sin(angle) * speed,
            len:   rnd(100, 260),
            alpha: 1,
            fade:  rnd(0.006, 0.014),
            color: COLORS[Math.floor(Math.random() * COLORS.length)],
            width: rnd(1, 2.2),
        };
    }

    function makeSparkle(w, h) {
        var totalFrames = Math.floor(rnd(60, 120));
        return {
            x:      rnd(20, w - 20),
            y:      rnd(20, h - 20),
            size:   rnd(4, 10),
            color:  COLORS[Math.floor(Math.random() * COLORS.length)],
            frame:  0,
            total:  totalFrames,
            rotate: rnd(0, Math.PI),
        };
    }

    function drawSparkleShape(ctx, x, y, size, rotate, color, alpha) {
        ctx.save();
        ctx.translate(x, y);
        ctx.rotate(rotate);
        ctx.globalAlpha = alpha;
        ctx.shadowBlur  = 12;
        ctx.shadowColor = color;

        ctx.beginPath();
        var points = 4;
        for (var i = 0; i < points * 2; i++) {
            var angle = (i * Math.PI) / points;
            var r = (i % 2 === 0) ? size : size * 0.2;
            if (i === 0) ctx.moveTo(Math.cos(angle) * r, Math.sin(angle) * r);
            else         ctx.lineTo(Math.cos(angle) * r, Math.sin(angle) * r);
        }
        ctx.closePath();
        ctx.fillStyle = color;
        ctx.fill();

        ctx.beginPath();
        ctx.arc(0, 0, size * 0.18, 0, Math.PI * 2);
        ctx.fillStyle = '#ffffff';
        ctx.globalAlpha = alpha * 0.9;
        ctx.shadowBlur  = 6;
        ctx.shadowColor = '#ffffff';
        ctx.fill();

        ctx.restore();
    }

    function drawStars(ctx, stars) {
        var dark = isDark();
        for (var i = 0; i < stars.length; i++) {
            var s = stars[i];
            s.alpha += s.delta;
            if (s.alpha > s.base + 0.3 || s.alpha < 0.1) s.delta *= -1;

            ctx.beginPath();
            ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
            ctx.fillStyle = dark
                ? 'rgba(255,255,255,' + s.alpha + ')'
                : 'rgba(80,80,130,'   + (s.alpha * 0.45) + ')';
            ctx.fill();
        }
    }

    function drawShots(ctx, shots, w, h) {
        for (var i = shots.length - 1; i >= 0; i--) {
            var s     = shots[i];
            var speed = Math.sqrt(s.vx * s.vx + s.vy * s.vy);
            var nx    = s.vx / speed;
            var ny    = s.vy / speed;
            var tx    = s.x - nx * s.len;
            var ty    = s.y - ny * s.len;

            var grad = ctx.createLinearGradient(tx, ty, s.x, s.y);
            grad.addColorStop(0,   'rgba(0,0,0,0)');
            grad.addColorStop(0.5, hexAlpha(s.color, 0.25 * s.alpha));
            grad.addColorStop(1,   hexAlpha(s.color, s.alpha));

            ctx.save();

            ctx.beginPath();
            ctx.moveTo(tx, ty);
            ctx.lineTo(s.x, s.y);
            ctx.strokeStyle = grad;
            ctx.lineWidth   = s.width * 3;
            ctx.globalAlpha = s.alpha * 0.25;
            ctx.shadowBlur  = 16;
            ctx.shadowColor = s.color;
            ctx.stroke();

            ctx.beginPath();
            ctx.moveTo(tx, ty);
            ctx.lineTo(s.x, s.y);
            ctx.strokeStyle = grad;
            ctx.lineWidth   = s.width;
            ctx.globalAlpha = s.alpha;
            ctx.shadowBlur  = 8;
            ctx.shadowColor = s.color;
            ctx.stroke();

            ctx.beginPath();
            ctx.arc(s.x, s.y, s.width * 1.2, 0, Math.PI * 2);
            ctx.fillStyle   = '#ffffff';
            ctx.globalAlpha = s.alpha * 0.9;
            ctx.shadowBlur  = 12;
            ctx.shadowColor = s.color;
            ctx.fill();

            ctx.restore();

            s.x     += s.vx;
            s.y     += s.vy;
            s.alpha -= s.fade;

            var oob = s.x < -s.len || s.y > h + s.len || s.x > w + s.len;
            if (s.alpha <= 0 || oob) shots.splice(i, 1);
        }
    }

    function drawSparkles(ctx, sparkles, w, h, ts, nextSparkle) {
        for (var i = sparkles.length - 1; i >= 0; i--) {
            var sp = sparkles[i];
            sp.frame++;
            var progress = sp.frame / sp.total;

            var alpha;
            if (progress < 0.3) {
                alpha = progress / 0.3;
            } else if (progress < 0.7) {
                alpha = 1;
            } else {
                alpha = (1 - progress) / 0.3;
            }
            alpha = Math.max(0, Math.min(1, alpha)) * 0.85;

            var scale = alpha < 1 ? alpha : 1;
            drawSparkleShape(ctx, sp.x, sp.y, sp.size * scale, sp.rotate + progress * Math.PI * 0.5, sp.color, alpha);

            if (sp.frame >= sp.total) sparkles.splice(i, 1);
        }
        return nextSparkle;
    }


    function init(canvas) {
        var ctx          = canvas.getContext('2d');
        var stars        = [];
        var shots        = [];
        var sparkles     = [];
        var nextSpawn    = 0;
        var nextSparkle  = 0;

        function resize() {
            canvas.width  = window.innerWidth;
            canvas.height = window.innerHeight;
            stars = [];
            for (var i = 0; i < STAR_COUNT; i++) {
                stars.push(makeStar(canvas.width, canvas.height));
            }
        }

        resize();
        window.addEventListener('resize', resize);

        function animate(ts) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            drawStars(ctx, stars);

            if (ts > nextSpawn && shots.length < MAX_SHOOTING) {
                shots.push(makeShooting(canvas.width, canvas.height));
                nextSpawn = ts + SPAWN_INTERVAL + rnd(-400, 400);
            }

            drawShots(ctx, shots, canvas.width, canvas.height);

            if (ts > nextSparkle && sparkles.length < MAX_SPARKLES) {
                sparkles.push(makeSparkle(canvas.width, canvas.height));
                nextSparkle = ts + SPARKLE_SPAWN + rnd(-150, 150);
            }

            drawSparkles(ctx, sparkles, canvas.width, canvas.height, ts, nextSparkle);

            requestAnimationFrame(animate);
        }

        requestAnimationFrame(animate);
    }


    document.addEventListener('DOMContentLoaded', function () {
        var canvas = document.getElementById('bg-beams');
        if (canvas) init(canvas);
    });

})();
