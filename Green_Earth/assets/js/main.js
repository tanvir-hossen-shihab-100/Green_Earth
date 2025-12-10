document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (navToggle) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }

    const flashClose = document.querySelector('.flash-close');
    if (flashClose) {
        flashClose.addEventListener('click', function() {
            this.closest('.flash-message').style.display = 'none';
        });

        setTimeout(function() {
            const flashMessage = document.querySelector('.flash-message');
            if (flashMessage) {
                flashMessage.style.opacity = '0';
                flashMessage.style.transition = 'opacity 0.5s ease';
                setTimeout(() => flashMessage.style.display = 'none', 500);
            }
        }, 5000);
    }

    const searchInput = document.getElementById('searchInput');
    const categoryBtns = document.querySelectorAll('.category-btn');
    const treeCards = document.querySelectorAll('.tree-card');

    function filterTrees() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const activeCategory = document.querySelector('.category-btn.active');
        const category = activeCategory ? activeCategory.dataset.category : 'All Trees';

        treeCards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const treeCategory = card.dataset.category;
            const scientificName = card.dataset.scientific ? card.dataset.scientific.toLowerCase() : '';

            const matchesSearch = name.includes(searchTerm) || scientificName.includes(searchTerm);
            const matchesCategory = category === 'All Trees' || treeCategory === category;

            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        updateNoResults();
    }

    function updateNoResults() {
        const visibleCards = document.querySelectorAll('.tree-card[style="display: block"]');
        const noResults = document.getElementById('noResults');
        
        if (noResults) {
            if (visibleCards.length === 0) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterTrees);
    }

    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filterTrees();
        });
    });

    if (treeCards.length > 0) {
        treeCards.forEach(card => card.style.display = 'block');
    }

    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const deleteBtns = document.querySelectorAll('.delete-btn');
    const cancelDelete = document.getElementById('cancelDelete');

    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const treeId = this.dataset.id;
            const treeName = this.dataset.name;
            
            document.getElementById('deleteTreeName').textContent = treeName;
            document.getElementById('deleteTreeId').value = treeId;
            deleteModal.classList.add('active');
        });
    });

    if (cancelDelete) {
        cancelDelete.addEventListener('click', function() {
            deleteModal.classList.remove('active');
        });
    }

    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                deleteModal.classList.remove('active');
            }
        });
    }

    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                const errorEl = field.parentElement.querySelector('.form-error');
                if (errorEl) errorEl.remove();
                
                if (!field.value.trim()) {
                    isValid = false;
                    const error = document.createElement('span');
                    error.className = 'form-error';
                    error.textContent = 'This field is required';
                    field.parentElement.appendChild(error);
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
});
