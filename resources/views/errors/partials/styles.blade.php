{{--
    Shared Styles untuk Halaman Error
    File ini berisi semua CSS yang digunakan oleh halaman error kustom.
--}}
<style>
    :root {
        --sikarir-primary: #00B074;
        --sikarir-primary-dark: #009A65;
        --sikarir-primary-light: #00C785;
        --sikarir-secondary: #1A1A2E;
        --sikarir-accent: #00D68F;
        --gradient-primary: linear-gradient(135deg, #00B074 0%, #00C785 50%, #00D68F 100%);
        --gradient-bg: linear-gradient(135deg, #f8fffe 0%, #e8f8f3 50%, #f0fdf8 100%);
        --text-primary: #1A1A2E;
        --text-secondary: #555;
        --shadow-card: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        --shadow-glow: 0 0 40px rgba(0, 176, 116, 0.2);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--gradient-bg);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .error-container {
        position: relative;
        width: 100%;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .error-content {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 60px 50px;
        max-width: 550px;
        width: 100%;
        text-align: center;
        box-shadow: var(--shadow-card);
        border: 1px solid rgba(0, 176, 116, 0.1);
        position: relative;
        z-index: 10;
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .error-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 30px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-glow);
        animation: pulse 2s infinite;
    }

    .error-icon i {
        font-size: 45px;
        color: white;
    }

    .error-icon-warning {
        background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
        box-shadow: 0 0 40px rgba(245, 158, 11, 0.3);
    }

    .error-icon-danger {
        background: linear-gradient(135deg, #EF4444 0%, #F87171 100%);
        box-shadow: 0 0 40px rgba(239, 68, 68, 0.3);
    }

    .error-icon-info {
        background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%);
        box-shadow: 0 0 40px rgba(59, 130, 246, 0.3);
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .error-code {
        font-size: 100px;
        font-weight: 800;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
        margin-bottom: 15px;
        letter-spacing: -3px;
    }

    .error-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 20px;
    }

    .error-description {
        font-size: 16px;
        color: var(--text-secondary);
        line-height: 1.7;
        margin-bottom: 35px;
    }

    .error-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .btn-primary-error, .btn-secondary-error {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-primary-error {
        background: var(--gradient-primary);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 176, 116, 0.3);
    }

    .btn-primary-error:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 176, 116, 0.4);
        color: white;
    }

    .btn-secondary-error {
        background: transparent;
        color: var(--sikarir-primary);
        border: 2px solid var(--sikarir-primary);
    }

    .btn-secondary-error:hover {
        background: rgba(0, 176, 116, 0.1);
        transform: translateY(-2px);
        color: var(--sikarir-primary);
    }

    .error-tips {
        background: rgba(0, 176, 116, 0.08);
        padding: 15px 20px;
        border-radius: 12px;
        border-left: 4px solid var(--sikarir-primary);
    }

    .error-tips p {
        font-size: 14px;
        color: var(--text-secondary);
        margin: 0;
    }

    .error-tips i {
        color: var(--sikarir-primary);
        margin-right: 5px;
    }

    /* Maintenance Progress Bar */
    .maintenance-progress {
        margin-top: 30px;
    }

    .progress-bar {
        height: 8px;
        background: rgba(0, 176, 116, 0.15);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 15px;
    }

    .progress-fill {
        height: 100%;
        width: 70%;
        background: var(--gradient-primary);
        border-radius: 10px;
        animation: progressPulse 2s ease-in-out infinite;
    }

    @keyframes progressPulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
    }

    .maintenance-progress p {
        font-size: 14px;
        color: var(--text-secondary);
    }

    /* Decorative Elements */
    .decoration {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.5;
        z-index: 1;
    }

    .decoration-1 {
        width: 400px;
        height: 400px;
        background: rgba(0, 176, 116, 0.3);
        top: -100px;
        right: -100px;
        animation: float 8s ease-in-out infinite;
    }

    .decoration-2 {
        width: 300px;
        height: 300px;
        background: rgba(0, 199, 133, 0.25);
        bottom: -50px;
        left: -100px;
        animation: float 10s ease-in-out infinite reverse;
    }

    .decoration-3 {
        width: 200px;
        height: 200px;
        background: rgba(0, 214, 143, 0.2);
        top: 50%;
        left: 10%;
        animation: float 12s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        50% {
            transform: translateY(-30px) rotate(5deg);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .error-content {
            padding: 40px 25px;
            margin: 0 15px;
        }

        .error-icon {
            width: 80px;
            height: 80px;
        }

        .error-icon i {
            font-size: 35px;
        }

        .error-code {
            font-size: 70px;
        }

        .error-title {
            font-size: 22px;
        }

        .error-description {
            font-size: 14px;
        }

        .error-actions {
            flex-direction: column;
        }

        .btn-primary-error, .btn-secondary-error {
            width: 100%;
            justify-content: center;
        }

        .decoration-1 {
            width: 250px;
            height: 250px;
        }

        .decoration-2 {
            width: 200px;
            height: 200px;
        }

        .decoration-3 {
            display: none;
        }
    }
</style>
