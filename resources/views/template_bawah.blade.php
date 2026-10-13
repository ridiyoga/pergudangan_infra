</div>

<button class="sidebar-backdrop d-none" id="sidebarBackdrop" type="button" aria-label="Tutup navigasi"></button>
<script>
    $(function() {
        const $sidebar = $('#sidebar');
        const $sidebarToggle = $('#sidebarToggle');
        const $sidebarBackdrop = $('#sidebarBackdrop');
        const mobileBreakpoint = window.matchMedia('(max-width: 991.98px)');
        let wasMobile = mobileBreakpoint.matches;

        function updateToggleLabel(isOpen) {
            $sidebarToggle
                .attr('aria-expanded', String(isOpen))
                .attr('aria-label', isOpen ? 'Tutup navigasi' : 'Buka navigasi');
        }

        function closeMobileSidebar() {
            $sidebar.removeClass('is-open');
            $sidebarBackdrop.addClass('d-none');
            updateToggleLabel(false);
        }

        updateToggleLabel(!mobileBreakpoint.matches);

        $sidebarToggle.on('click', function() {
            if (mobileBreakpoint.matches) {
                const isOpen = !$sidebar.hasClass('is-open');
                $sidebar.toggleClass('is-open', isOpen);
                $sidebarBackdrop.toggleClass('d-none', !isOpen);
                updateToggleLabel(isOpen);
                return;
            }

            const isCollapsed = !$sidebar.hasClass('is-collapsed');
            $sidebar.toggleClass('is-collapsed', isCollapsed);
            updateToggleLabel(!isCollapsed);
        });

        $('#sidebarClose, #sidebarBackdrop').on('click', closeMobileSidebar);
        $('.sidebar-menu-toggle').on('click', function() {
            const $menuToggle = $(this);
            const isExpanded = $menuToggle.attr('aria-expanded') === 'true';
            $menuToggle.attr('aria-expanded', String(!isExpanded));
            $('#' + $menuToggle.attr('aria-controls')).stop(true, true).slideToggle(180);
        });
        $(document).on('keydown', function(event) {
            if (event.key === 'Escape' && mobileBreakpoint.matches) {
                closeMobileSidebar();
            }
        });
        $(window).on('resize', function() {
            const isMobile = mobileBreakpoint.matches;
            if (isMobile === wasMobile) {
                return;
            }

            wasMobile = isMobile;
            $sidebar.removeClass('is-open is-collapsed');
            $sidebarBackdrop.addClass('d-none');
            updateToggleLabel(!isMobile);
        });
    });
</script>
</body>

</html>
