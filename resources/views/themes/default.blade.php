<div id="lucky-wheel-widget" class="lw-theme-default">
    <!-- Floating Trigger Button -->
    <button id="lw-trigger-btn" class="lw-floating-btn" onclick="openLuckyWheelModal()">
        <span class="lw-btn-icon">🎁</span>
        <span class="lw-btn-text">{{ __('Try your luck!') }}</span>
    </button>

    <!-- Modal Overlay -->
    <div id="lw-modal-overlay" class="lw-modal-overlay" style="display: none;">
        <div class="lw-modal-content">
            <button class="lw-close-btn" onclick="closeLuckyWheelModal()">&times;</button>
            <div class="lw-modal-header">
                <h3 class="lw-title">{{ __('Lucky Wheel') }}</h3>
                <p class="lw-subtitle">{{ __('Spin the wheel and win exclusive discounts!') }}</p>
            </div>
            
            <div class="lw-wheel-container">
                <div class="lw-pointer">▼</div>
                <canvas id="lw-canvas"></canvas>
            </div>

            <div class="lw-form-container">
                @if($settings->email_policy === 'before_spin' && !auth()->check())
                    <div id="lw-email-step">
                        <input type="email" id="lw-input-email" class="lw-input" placeholder="{{ __('Enter your email address...') }}" required>
                    </div>
                @endif

                <button id="lw-spin-btn" class="lw-spin-btn" onclick="spinLuckyWheel()">{{ __('Spin the Wheel!') }}</button>
                <div id="lw-error-msg" class="lw-error" style="display: none;"></div>
            </div>

            <div id="lw-claim-step" class="lw-claim-container" style="display: none;">
                <h4 class="lw-result-title">{{ __('Congratulations!') }}</h4>
                <p class="lw-result-desc">{{ __('Please enter your email to reveal your discount code.') }}</p>
                <input type="email" id="lw-claim-email" class="lw-input" placeholder="{{ __('Enter your email address...') }}" required>
                <button id="lw-claim-btn" class="lw-spin-btn" onclick="claimLuckyPrize()">{{ __('Claim Prize') }}</button>
                <div id="lw-claim-error" class="lw-error" style="display: none;"></div>
            </div>

            <div id="lw-result-container" class="lw-result-container" style="display: none;">
                <h4 id="lw-result-title" class="lw-result-title">{{ __('Congratulations!') }}</h4>
                <p id="lw-result-desc" class="lw-result-desc"></p>
                <div id="lw-code-box" class="lw-code-box" style="display: none;">
                    <span id="lw-promo-code" class="lw-code-text"></span>
                    <button onclick="copyLuckyCode()" class="lw-copy-btn">{{ __('Copy') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.lw-theme-default { font-family: 'Inter', system-ui, -apple-system, sans-serif; box-sizing: border-box; }
.lw-theme-default *, .lw-theme-default *::before, .lw-theme-default *::after { box-sizing: inherit; }

.lw-floating-btn {
    position: fixed; bottom: 20px; left: 20px; z-index: 9998;
    background: linear-gradient(135deg, #1e293b, #0f172a); border: 2px solid #e2e8f0;
    color: #fff; padding: 10px 18px; border-radius: 50px; cursor: pointer;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3); display: flex; align-items: center; gap: 8px;
    transition: transform 0.3s, box-shadow 0.3s;
}
.lw-floating-btn:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(0,0,0,0.4); }
.lw-btn-icon { font-size: 20px; animation: bounce 2s infinite; }
.lw-btn-text { font-weight: 600; font-size: 14px; }

.lw-modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;
    background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px);
    display: flex; align-items: center; justify-content: center; padding: 15px;
}
.lw-modal-content {
    background: #1e293b; border: 1px solid #334155; border-radius: 20px;
    width: 100%; max-width: 420px; padding: 25px 20px; position: relative;
    box-shadow: 0 25px 50px rgba(0,0,0,0.5); text-align: center; color: #f8fafc;
    max-height: 92vh; overflow-y: auto;
}
.lw-close-btn {
    position: absolute; top: 12px; right: 15px; background: none; border: none;
    color: #94a3b8; font-size: 26px; cursor: pointer; transition: color 0.2s;
    line-height: 1; padding: 4px; z-index: 20;
}
.lw-close-btn:hover { color: #f8fafc; }
.lw-title { margin: 0 0 6px 0; font-size: 22px; font-weight: 700; color: #f1f5f9; padding-right: 25px; }
.lw-subtitle { margin: 0 0 16px 0; font-size: 13px; color: #94a3b8; line-height: 1.4; }

.lw-wheel-container {
    position: relative; margin: 0 auto 20px auto;
    width: 100%; max-width: 310px; aspect-ratio: 1 / 1;
}
.lw-pointer {
    position: absolute; top: -12px; left: 50%; transform: translateX(-50%);
    font-size: 28px; color: #ef4444; z-index: 10; text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}
#lw-canvas { width: 100%; height: 100%; display: block; transition: transform 5s cubic-bezier(0.17, 0.67, 0.12, 0.99); }

.lw-input {
    width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid #475569;
    background: #0f172a; color: #fff; font-size: 14px; margin-bottom: 12px; outline: none;
}
.lw-input:focus { border-color: #38bdf8; }
.lw-spin-btn {
    width: 100%; padding: 14px; background: linear-gradient(135deg, #3b82f6, #2563eb);
    border: none; border-radius: 10px; color: #fff; font-size: 15px; font-weight: 600;
    cursor: pointer; transition: filter 0.2s;
}
.lw-spin-btn:hover { filter: brightness(1.1); }
.lw-spin-btn:disabled { background: #64748b; cursor: not-allowed; }

.lw-error { color: #f87171; font-size: 13px; margin-top: 10px; }
.lw-result-container, .lw-claim-container { margin-top: 18px; padding-top: 18px; border-top: 1px solid #334155; }
.lw-result-title { font-size: 19px; color: #38bdf8; margin: 0 0 6px 0; font-weight: 700; }
.lw-result-desc { font-size: 14px; color: #e2e8f0; margin: 0 0 14px 0; line-height: 1.4; }
.lw-code-box {
    background: #0f172a; border: 1px dashed #38bdf8; padding: 10px 14px;
    border-radius: 8px; display: flex; align-items: center; justify-content: space-between; gap: 10px;
}
.lw-code-text { font-family: monospace; font-size: 15px; font-weight: 700; color: #38bdf8; word-break: break-all; }
.lw-copy-btn {
    background: #38bdf8; color: #0f172a; border: none; padding: 6px 12px;
    border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 13px; flex-shrink: 0;
}
@keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }
</style>

<script>
(function() {
    const prizes = @json($prizes);
    const numSectors = prizes.length;
    const arc = (2 * Math.PI) / (numSectors || 1);
    const autoOpenSeconds = {{ (int) ($settings->auto_open_seconds ?? 0) }};
    let canvas, ctx;
    let currentRotation = 0;
    let activeSpinId = null;

    window.openLuckyWheelModal = function() {
        document.getElementById('lw-modal-overlay').style.display = 'flex';
        sessionStorage.setItem('lw_seen', '1');
        initWheel();
    };

    window.closeLuckyWheelModal = function() {
        document.getElementById('lw-modal-overlay').style.display = 'none';
        sessionStorage.setItem('lw_seen', '1');
    };

    function initWheel() {
        canvas = document.getElementById('lw-canvas');
        if (!canvas || !canvas.getContext) return;
        ctx = canvas.getContext('2d');
        
        const size = 340;
        const dpr = window.devicePixelRatio || 1;
        canvas.width = size * dpr;
        canvas.height = size * dpr;
        ctx.scale(dpr, dpr);
        
        drawWheel();
    }

    function drawWheel() {
        const centerX = 170, centerY = 170, radius = 160;
        ctx.clearRect(0, 0, 340, 340);

        prizes.forEach((prize, i) => {
            const angle = i * arc - Math.PI / 2;
            ctx.beginPath();
            ctx.fillStyle = prize.bg_color || '#334155';
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, radius, angle, angle + arc, false);
            ctx.lineTo(centerX, centerY);
            ctx.fill();
            ctx.save();

            ctx.translate(centerX, centerY);
            ctx.rotate(angle + arc / 2);
            ctx.textAlign = 'right';
            ctx.textBaseline = 'middle';
            ctx.font = 'bold 13px system-ui, -apple-system, sans-serif';
            ctx.fillStyle = prize.text_color || '#ffffff';
            
            let title = prize.title || '';
            if (title.length > 20) title = title.substring(0, 18) + '...';
            ctx.fillText(title, radius - 18, 0);
            ctx.restore();
        });
    }

    if (autoOpenSeconds > 0 && !sessionStorage.getItem('lw_seen')) {
        setTimeout(() => {
            if (document.getElementById('lw-modal-overlay').style.display !== 'flex') {
                openLuckyWheelModal();
            }
        }, autoOpenSeconds * 1000);
    }

    window.spinLuckyWheel = async function() {
        const btn = document.getElementById('lw-spin-btn');
        const errorMsg = document.getElementById('lw-error-msg');
        const emailInput = document.getElementById('lw-input-email');
        
        errorMsg.style.display = 'none';
        let email = emailInput ? emailInput.value : null;

        btn.disabled = true;
        btn.textContent = '{{ __("Spinning...") }}';

        try {
            const res = await fetch('{{ route("lucky-wheel.spin") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            });
            const data = await res.json();

            if (!res.ok || !data.success) {
                errorMsg.textContent = data.message || '{{ __("Error spinning the wheel.") }}';
                errorMsg.style.display = 'block';
                btn.disabled = false;
                btn.textContent = '{{ __("Spin the Wheel!") }}';
                return;
            }

            activeSpinId = data.spin_id;
            const winIndex = data.prize_index;
            const degreesPerSector = 360 / numSectors;
            const targetSectorAngle = winIndex * degreesPerSector + (degreesPerSector / 2);
            const spinRounds = 360 * 5;
            const finalAngle = spinRounds + (360 - targetSectorAngle);
            
            currentRotation += finalAngle;
            canvas.style.transform = `rotate(${currentRotation}deg)`;

            setTimeout(() => {
                document.querySelector('.lw-form-container').style.display = 'none';
                if (data.requires_email) {
                    document.getElementById('lw-claim-step').style.display = 'block';
                } else {
                    document.getElementById('lw-result-container').style.display = 'block';
                    document.getElementById('lw-result-desc').textContent = data.message;
                    if (data.promo_code) {
                        document.getElementById('lw-code-box').style.display = 'flex';
                        document.getElementById('lw-promo-code').textContent = data.promo_code;
                    }
                }
            }, 5000);

        } catch (err) {
            errorMsg.textContent = '{{ __("Network error. Please try again.") }}';
            errorMsg.style.display = 'block';
            btn.disabled = false;
            btn.textContent = '{{ __("Spin the Wheel!") }}';
        }
    };

    window.claimLuckyPrize = async function() {
        const btn = document.getElementById('lw-claim-btn');
        const email = document.getElementById('lw-claim-email').value;
        const errorMsg = document.getElementById('lw-claim-error');
        
        errorMsg.style.display = 'none';
        if (!email) {
            errorMsg.textContent = '{{ __("Please enter your email address to claim your prize.") }}';
            errorMsg.style.display = 'block';
            return;
        }

        btn.disabled = true;
        btn.textContent = '...';

        try {
            const res = await fetch('{{ route("lucky-wheel.claim") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ spin_id: activeSpinId, email: email })
            });
            const data = await res.json();

            if (!res.ok || !data.success) {
                errorMsg.textContent = data.message || '{{ __("Error spinning the wheel.") }}';
                errorMsg.style.display = 'block';
                btn.disabled = false;
                btn.textContent = '{{ __("Claim Prize") }}';
                return;
            }

            document.getElementById('lw-claim-step').style.display = 'none';
            document.getElementById('lw-result-container').style.display = 'block';
            document.getElementById('lw-result-desc').textContent = data.message;
            if (data.promo_code) {
                document.getElementById('lw-code-box').style.display = 'flex';
                document.getElementById('lw-promo-code').textContent = data.promo_code;
            }
        } catch (err) {
            errorMsg.textContent = '{{ __("Network error. Please try again.") }}';
            errorMsg.style.display = 'block';
            btn.disabled = false;
            btn.textContent = '{{ __("Claim Prize") }}';
        }
    };

    window.copyLuckyCode = function() {
        const code = document.getElementById('lw-promo-code').textContent;
        navigator.clipboard.writeText(code);
        alert('{{ __("Discount code copied!") }}');
    };
})();
</script>
