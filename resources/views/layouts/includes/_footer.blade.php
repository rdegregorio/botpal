<footer class="footer mt-auto">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <a href="/" class="d-flex align-items-center gap-2 text-decoration-none">
                    <span class="d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; background: #1a1a1a; border-radius: 6px; color: white; font-size: 12px;">
                        <i class="bi bi-chat-dots-fill"></i>
                    </span>
                    <span style="font-weight: 600; font-size: 14px; color: #1a1a1a;">aisupport.bot</span>
                </a>
                <p class="mb-0 mt-2" style="font-size: 13px;">&copy; {{ date('Y') }} aisupport.bot. All rights reserved.</p>
            </div>
            <div class="col-md-8 text-md-end">
                <a href="{{route('pricing')}}" class="text-link">Pricing</a>
                <a href="{{route('pages.terms')}}" class="text-link">Terms</a>
                <a href="{{route('pages.privacy')}}" class="text-link">Privacy</a>
                <a href="{{route('pages.contact')}}" class="text-link">Contact</a>
            </div>
        </div>
    </div>
</footer>
