<div id="lucky-wheel-widget" class="lw-theme-christmas">
    <!-- Floating Trigger Button -->
    <button id="lw-trigger-btn" class="lw-floating-btn-xmas" onclick="openLuckyWheelModal()">
        <span class="lw-btn-icon">🎄</span>
        <span class="lw-btn-text">{{ __('Christmas Lucky Wheel!') }}</span>
    </button>

    <!-- Modal Overlay -->
    <div id="lw-modal-overlay" class="lw-modal-overlay" style="display: none;">
        <div class="lw-modal-content-xmas">
            <div class="lw-snowflakes" aria-hidden="true">
                <div class="snowflake">❅</div><div class="snowflake">❆</div><div class="snowflake">❅</div><div class="snowflake">❆</div>
            </div>
            <button class="lw-close-btn" onclick="closeLuckyWheelModal()">&times;</button>
            <div class="lw-modal-header">
                <h3 class="lw-title">{{ __('🎅 Festive Lucky Wheel 🎁') }}</h3>
                <p class="lw-subtitle">{{ __('Spin for your Christmas gift or discount!') }}</p>
            </div>
            
            <div class="lw-wheel-container">
                <div class="lw-pointer">⭐</div>
                <canvas id="lw-canvas" width="340" height="340"></canvas>
            </div>

            <div class="lw-form-container">
                @if($settings->email_policy === 'before_spin')
                    <div id="lw-email-step">
                        <input type="email" id="lw-input-email" class="lw-input" placeholder="{{ __('Your email for the gift...') }}" required>
                    </div>
                @endif

                <button id="lw-spin-btn" class="lw-spin-btn-xmas" onclick="spinLuckyWheel()">{{ __('Spin Christmas Wheel!') }}</button>
                <div id="lw-error-msg" class="lw-error" style="display: none;"></div>
            </div>

            <div id="lw-claim-step" class="lw-claim-container" style="display: none;">
                <h4 class="lw-result-title">{{ __('🎄 Hurray! Festive win!') }}</h4>
                <p class="lw-result-desc">{{ __('Please enter your email to reveal your discount code.') }}</p>
                <input type="email" id="lw-claim-email" class="lw-input" placeholder="{{ __('Your email for the gift...') }}" required>
                <button id="lw-claim-btn" class="lw-spin-btn-xmas" onclick="claimLuckyPrize()">{{ __('Claim Prize') }}</button>
                <div id="lw-claim-error" class="lw-error" style="display: none;"></div>
            </div>

            <div id="lw-result-container" class="lw-result-container" style="display: none;">
                <h4 id="lw-result-title" class="lw-result-title">{{ __('🎄 Hurray! Festive win!') }}</h4>
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
.lw-theme-christmas { font-family: 'Inter', system-ui, sans-serif; }
.lw-floating-btn-xmas {
    position: fixed; bottom: 25px; left: 25px; z-index: 9998;
    background: linear-gradient(135deg, #dc2626, #16a34a); border: 2px solid #fef08a;
    color: #fff; padding: 12px 20px; border-radius: 50px; cursor: pointer;
    box-shadow: 0 10px 25px rgba(220,38,38,0.4); display: flex; align-items: center; gap: 10px;
    transition: transform 0.3s;
}
.lw-floating-btn-xmas:hover { transform: translateY(-3px) scale(1.05); }
.lw-btn-icon { font-size: 20px; animation: bounce 2s infinite; }
.lw-btn-text { font-weight: 700; font-size: 15px; color: #fef08a; text-shadow: 0 1px 2px rgba(0,0,0,0.5); }

.lw-modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;
    background: rgba(10, 20, 15, 0.85); backdrop-filter: blur(8px);
    display: flex; align-items: center; justify-content: center; padding: 20px;
}
.lw-modal-content-xmas {
    background: linear-gradient(145deg, #14532d, #7f1d1d); border: 2px solid #facc15; border-radius: 24px;
    width: 100%; max-width: 440px; padding: 30px; position: relative; overflow: hidden;
    box-shadow: 0 25px 50px rgba(0,0,0,0.6); text-align: center; color: #f8fafc;
}
.lw-close-btn {
    position: absolute; top: 15px; right: 20px; background: none; border: none;
    color: #facc15; font-size: 28px; cursor: pointer; z-index: 20;
}
.lw-title { margin: 0 0 8px 0; font-size: 24px; font-weight: 800; color: #fef08a; }
.lw-subtitle { margin: 0 0 20px 0; font-size: 14px; color: #e2e8f0; }

.lw-wheel-container { position: relative; margin: 0 auto 25px auto; width: 340px; height: 340px; }
.lw-pointer {
    position: absolute; top: -16px; left: 50%; transform: translateX(-50%);
    font-size: 32px; color: #facc15; z-index: 10; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.6));
}
#lw-canvas { width: 100%; height: 100%; transition: transform 5s cubic-bezier(0.17, 0.67, 0.12, 0.99); }

.lw-input {
    width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid #facc15;
    background: rgba(0,0,0,0.3); color: #fff; font-size: 14px; margin-bottom: 12px;
}
.lw-spin-btn-xmas {
    width: 100%; padding: 14px; background: linear-gradient(135deg, #e11d48, #be123c);
    border: 2px solid #fef08a; border-radius: 12px; color: #fff; font-size: 16px; font-weight: 700;
    cursor: pointer; box-shadow: 0 4px 12px rgba(225,29,72,0.4);
}
.lw-spin-btn-xmas:hover { filter: brightness(1.1); }
.lw-error { color: #fca5a5; font-size: 13px; margin-top: 10px; }
.lw-result-container, .lw-claim-container { margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2); }
.lw-result-title { font-size: 20px; color: #fde047; margin: 0 0 8px 0; }
.lw-result-desc { font-size: 15px; color: #fff; margin: 0 0 15px 0; }
.lw-code-box {
    background: rgba(0,0,0,0.4); border: 1px dashed #fde047; padding: 10px 15px;
    border-radius: 8px; display: flex; align-items: center; justify-content: space-between;
}
.lw-code-text { font-family: monospace; font-size: 16px; font-weight: 700; color: #fde047; }
.lw-copy-btn { background: #fde047; color: #7f1d1d; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 700; cursor: pointer; }

/* Snowflakes */
.lw-snowflakes { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; overflow: hidden; }
.snowflake { position: absolute; top: -20px; color: rgba(255,255,255,0.6); font-size: 20px; animation: fall 4s linear infinite; }
.snowflake:nth-child(1) { left: 10%; animation-delay: 0s; }
.snowflake:nth-child(2) { left: 40%; animation-delay: 1s; }
.snowflake:nth-child(3) { left: 70%; animation-delay: 2s; }
.snowflake:nth-child(4) { left: 90%; animation-delay: 1.5s; }
@keyframes fall { 0% { transform: translateY(0); } 100% { transform: translateY(480px); } }
@keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }
</style>

<script>
(function() {
    const prizes = @json($prizes);
    const numSectors = prizes.length;
    const arc = (2 * Math.PI) / (numSectors || 1);
    let canvas, ctx;
    let currentRotation = 0;
    let activeSpinId = null;

    window.openLuckyWheelModal = function() {
        document.getElementById('lw-modal-overlay').style.display = 'flex';
        initWheel();
    };

    window.closeLuckyWheelModal = function() {
        document.getElementById('lw-modal-overlay').style.display = 'none';
    };

    function initWheel() {
        canvas = document.getElementById('lw-canvas');
        if (!canvas || !canvas.getContext) return;
        ctx = canvas.getContext('2d');
        drawWheel();
    }

    function drawWheel() {
        ctx.clearRect(0, 0, 340, 340);
        const centerX = 170, centerY = 170, radius = 160;

        prizes.forEach((prize, i) => {
            const angle = i * arc - Math.PI / 2;
            ctx.beginPath();
            ctx.fillStyle = prize.bg_color || (i % 2 === 0 ? '#dc2626' : '#16a34a');
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, radius, angle, angle + arc, false);
            ctx.lineTo(centerX, centerY);
            ctx.fill();
            ctx.save();

            ctx.fillStyle = prize.text_color || '#ffffff';
            ctx.font = 'bold 13px sans-serif';
            ctx.translate(
                centerX + Math.cos(angle + arc / 2) * (radius - 50),
                centerY + Math.sin(angle + arc / 2) * (radius - 50)
            );
            ctx.rotate(angle + arc / 2 + Math.PI / 2);
            ctx.fillText(prize.title.substring(0, 16), -ctx.measureText(prize.title.substring(0, 16)).width / 2, 0);
            ctx.restore();
        });
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
                btn.textContent = '{{ __("Spin Christmas Wheel!") }}';
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
            btn.textContent = '{{ __("Spin Christmas Wheel!") }}';
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
