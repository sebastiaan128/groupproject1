(function () {
    var defaults = {
        background:      'transparent',
        minSize:         0.4,
        maxSize:         1.5,
        speed:           1,
        particleColor:   '#ffffff',
        particleDensity: 800,
    };

    function SparklesCore(canvas, options) {
        this.canvas  = canvas;
        this.ctx     = canvas.getContext('2d');
        this.options = Object.assign({}, defaults, options || {});
        this.particles = [];
        this.raf     = null;

        this._resize = this.resize.bind(this);
        window.addEventListener('resize', this._resize);
        this.resize();
        this.init();
        this.loop();
    }

    SparklesCore.prototype.resize = function () {
        var rect = this.canvas.parentElement
            ? this.canvas.parentElement.getBoundingClientRect()
            : { width: window.innerWidth, height: window.innerHeight };
        this.canvas.width  = rect.width  || window.innerWidth;
        this.canvas.height = rect.height || window.innerHeight;
        this.init();
    };

    SparklesCore.prototype.init = function () {
        this.particles = [];
        var count = Math.floor((this.canvas.width * this.canvas.height) / (1920 * 1080 / this.options.particleDensity));
        for (var i = 0; i < count; i++) {
            this.particles.push(this.makeParticle(true));
        }
    };

    SparklesCore.prototype.makeParticle = function (randomY) {
        var o = this.options;
        var size = o.minSize + Math.random() * (o.maxSize - o.minSize);
        return {
            x:      Math.random() * this.canvas.width,
            y:      randomY ? Math.random() * this.canvas.height : this.canvas.height + size,
            size:   size,
            speedX: (Math.random() - 0.5) * 0.3 * o.speed,
            speedY: -(0.2 + Math.random() * 0.5) * o.speed,
            life:   0,
            maxLife: 0.6 + Math.random() * 0.4,
            progress: randomY ? Math.random() : 0,
        };
    };

    SparklesCore.prototype.hexToRgb = function (hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result
            ? { r: parseInt(result[1], 16), g: parseInt(result[2], 16), b: parseInt(result[3], 16) }
            : { r: 255, g: 255, b: 255 };
    };

    SparklesCore.prototype.loop = function () {
        var self = this;
        self.raf = requestAnimationFrame(function () { self.loop(); });
        self.draw();
    };

    SparklesCore.prototype.draw = function () {
        var ctx  = this.ctx;
        var w    = this.canvas.width;
        var h    = this.canvas.height;
        var o    = this.options;
        var rgb  = this.hexToRgb(o.particleColor);

        ctx.clearRect(0, 0, w, h);

        if (o.background && o.background !== 'transparent') {
            ctx.fillStyle = o.background;
            ctx.fillRect(0, 0, w, h);
        }

        for (var i = this.particles.length - 1; i >= 0; i--) {
            var p = this.particles[i];

            p.progress += 0.004 * o.speed;
            p.x += p.speedX;
            p.y += p.speedY;

            // alpha: fade in then fade out using a sine curve
            var alpha = Math.sin(p.progress * Math.PI) * p.maxLife;
            alpha = Math.max(0, Math.min(1, alpha));

            // remove particle when it has faded out after peak
            if (p.progress >= 1 || p.y + p.size < 0) {
                this.particles[i] = this.makeParticle(false);
                continue;
            }

            this.drawSparkle(ctx, p.x, p.y, p.size, rgb, alpha);
        }
    };

    SparklesCore.prototype.drawSparkle = function (ctx, x, y, size, rgb, alpha) {
        // 4-pointed star sparkle shape
        ctx.save();
        ctx.translate(x, y);
        ctx.fillStyle = 'rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ',' + alpha + ')';

        var s = size;
        ctx.beginPath();
        // top
        ctx.moveTo(0, -s * 2);
        ctx.quadraticCurveTo(s * 0.4, -s * 0.4, s * 2, 0);
        // right
        ctx.quadraticCurveTo(s * 0.4, s * 0.4, 0, s * 2);
        // bottom
        ctx.quadraticCurveTo(-s * 0.4, s * 0.4, -s * 2, 0);
        // left
        ctx.quadraticCurveTo(-s * 0.4, -s * 0.4, 0, -s * 2);
        ctx.closePath();
        ctx.fill();

        // inner glow dot
        ctx.beginPath();
        ctx.arc(0, 0, s * 0.5, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ',' + Math.min(1, alpha * 1.5) + ')';
        ctx.fill();

        ctx.restore();
    };

    SparklesCore.prototype.destroy = function () {
        if (this.raf) cancelAnimationFrame(this.raf);
        window.removeEventListener('resize', this._resize);
    };

    // Auto-init: find all [data-sparkles] canvases
    function init() {
        var canvases = document.querySelectorAll('[data-sparkles]');
        canvases.forEach(function (canvas) {
            var opts = {};
            if (canvas.dataset.background)      opts.background      = canvas.dataset.background;
            if (canvas.dataset.particleColor)   opts.particleColor   = canvas.dataset.particleColor;
            if (canvas.dataset.particleDensity) opts.particleDensity = parseInt(canvas.dataset.particleDensity);
            if (canvas.dataset.minSize)         opts.minSize         = parseFloat(canvas.dataset.minSize);
            if (canvas.dataset.maxSize)         opts.maxSize         = parseFloat(canvas.dataset.maxSize);
            if (canvas.dataset.speed)           opts.speed           = parseFloat(canvas.dataset.speed);
            new SparklesCore(canvas, opts);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.SparklesCore = SparklesCore;
})();
