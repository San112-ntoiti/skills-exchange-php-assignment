document.addEventListener('DOMContentLoaded', function() {
    setupValidation();
    setupAjaxSearch();
});

function setupValidation() {
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');
    const addSkillForm = document.getElementById('addSkillForm');
    const editSkillForm = document.getElementById('editSkillForm');
    const requestForm = document.getElementById('requestForm');

    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            const username = registerForm.username.value.trim();
            const email = registerForm.email.value.trim();
            const password = registerForm.password.value;
            const confirmPassword = registerForm.confirm_password.value;
            let errors = [];

            if (username.length < 3) {
                errors.push('Username must be at least 3 characters.');
            }
            if (!validateEmail(email)) {
                errors.push('Enter a valid email address.');
            }
            if (password.length < 6) {
                errors.push('Password must be at least 6 characters.');
            }
            if (password !== confirmPassword) {
                errors.push('Passwords do not match.');
            }

            if (errors.length) {
                event.preventDefault();
                alert(errors.join('\n'));
            }
        });
    }

    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            const email = loginForm.email.value.trim();
            const password = loginForm.password.value;
            let errors = [];

            if (!validateEmail(email)) {
                errors.push('Enter a valid email address.');
            }
            if (password.length === 0) {
                errors.push('Password is required.');
            }

            if (errors.length) {
                event.preventDefault();
                alert(errors.join('\n'));
            }
        });
    }

    if (addSkillForm) {
        addSkillForm.addEventListener('submit', function(event) {
            validateSkillForm(event, addSkillForm);
        });
    }

    if (editSkillForm) {
        editSkillForm.addEventListener('submit', function(event) {
            validateSkillForm(event, editSkillForm);
        });
    }

    if (requestForm) {
        requestForm.addEventListener('submit', function(event) {
            const desiredSkill = requestForm.desired_skill.value.trim();
            let errors = [];

            if (desiredSkill.length < 3) {
                errors.push('Offer at least 3 characters for the skill you can share.');
            }

            if (errors.length) {
                event.preventDefault();
                alert(errors.join('\n'));
            }
        });
    }
}

function validateSkillForm(event, form) {
    const title = form.title.value.trim();
    const category = form.category.value;
    const description = form.description.value.trim();
    const skillLevel = form.skill_level.value;
    let errors = [];

    if (title.length < 3) {
        errors.push('Skill title must be at least 3 characters.');
    }
    if (!category) {
        errors.push('Please select a category.');
    }
    if (description.length < 10) {
        errors.push('Description must be at least 10 characters.');
    }
    if (!['beginner', 'intermediate', 'advanced'].includes(skillLevel)) {
        errors.push('Select a valid skill level.');
    }

    if (errors.length) {
        event.preventDefault();
        alert(errors.join('\n'));
    }
}

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\\.,;:\s@\"]+\.)+[^<>()[\]\\.,;:\s@\"]{2,})$/i;
    return re.test(String(email).toLowerCase());
}

function setupAjaxSearch() {
    const searchForm = document.querySelector('.search-form');
    if (!searchForm) {
        return;
    }

    const searchInput = searchForm.querySelector('input[name="search"]');
    const resultsContainer = document.querySelector('.search-results');

    searchForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const query = searchInput.value.trim();

        if (!query) {
            alert('Please enter a search term.');
            return;
        }

        fetch(`search.php?search=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message || 'Search failed.');
                    return;
                }
                renderSearchResults(data.results, resultsContainer, query);
            })
            .catch(() => {
                alert('Unable to complete the search at this time.');
            });
    });
}

function renderSearchResults(results, container, query) {
    if (!container) {
        return;
    }

    container.innerHTML = '';
    const title = document.createElement('h5');
    title.textContent = `Search Results for "${query}"`;
    container.appendChild(title);

    if (results.length === 0) {
        const empty = document.createElement('div');
        empty.className = 'empty-state';
        empty.innerHTML = '<i class="fas fa-search"></i><p>No skills found matching your search.</p>';
        container.appendChild(empty);
        return;
    }

    const row = document.createElement('div');
    row.className = 'row mt-3';

    results.forEach(skill => {
        const col = document.createElement('div');
        col.className = 'col-md-6 mb-3';
        col.innerHTML = `
            <div class="skill-card">
                <div class="skill-title">${escapeHtml(skill.title)}</div>
                <div class="skill-meta"><strong>By:</strong> ${escapeHtml(skill.username)}</div>
                <div class="skill-meta"><strong>Category:</strong> ${escapeHtml(skill.category)}</div>
                <div><span class="skill-level ${escapeHtml(skill.skill_level)}">${escapeHtml(capitalize(skill.skill_level))}</span></div>
                <p class="mt-2">${escapeHtml(skill.description.substring(0, 100))}...</p>
                <a href="request.php?skill_id=${skill.id}&user_id=${skill.user_id}" class="btn-small btn-request">Request Skill</a>
            </div>
        `;
        row.appendChild(col);
    });

    container.appendChild(row);
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
}

function capitalize(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
}
