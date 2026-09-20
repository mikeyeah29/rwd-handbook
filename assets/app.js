(function () {
    const input = document.querySelector('#handbook-filter');
    if (!input) return;

    const items = Array.from(document.querySelectorAll('[data-filter-item]'));
    const empty = document.querySelector('#filter-empty');

    input.addEventListener('input', function (event) {
        const query = event.target.value.trim().toLowerCase();
        let visible = 0;

        items.forEach(function (item) {
            const matches = item.dataset.filterText.toLowerCase().includes(query);
            item.hidden = !matches;
            if (matches) visible += 1;
        });

        if (empty) empty.hidden = visible !== 0;
    });
}());
