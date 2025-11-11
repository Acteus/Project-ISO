document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('studentForm');
  if (!form) {
    return;
  }

  const sectionDropdown = document.getElementById('section');
  const yearRadios = Array.from(document.querySelectorAll('input[name="year"]'));

  const sectionsByYear = {
    '11': ['C11a', 'C11b', 'C11c'],
    '12': ['C12a', 'C12b', 'C12c'],
  };

  const oldYear = form.dataset.oldYear || '';
  const oldSection = form.dataset.oldSection || '';

  const renderSections = (year, selectedSection = '') => {
    if (!sectionDropdown) {
      return;
    }

    const options = sectionsByYear[year] || [];
    const placeholder = year ? '-- Select Section --' : '-- Select Year First --';

    sectionDropdown.innerHTML = '';

    const placeholderOption = document.createElement('option');
    placeholderOption.value = '';
    placeholderOption.textContent = placeholder;
    sectionDropdown.appendChild(placeholderOption);

    options.forEach((section) => {
      const option = document.createElement('option');
      option.value = section;
      option.textContent = section;
      if (section === selectedSection) {
        option.selected = true;
      }
      sectionDropdown.appendChild(option);
    });
  };

  const handleYearChange = (event) => {
    const selectedYear = event.target.value;
    renderSections(selectedYear);
  };

  yearRadios.forEach((radio) => {
    radio.addEventListener('change', handleYearChange);
  });

  if (oldYear) {
    const radio = yearRadios.find((item) => item.value === oldYear);
    if (radio) {
      radio.checked = true;
      renderSections(oldYear, oldSection);
    }
  } else {
    renderSections('');
  }

  form.addEventListener('submit', (event) => {
    let isValid = true;
    const formGroups = Array.from(form.querySelectorAll('.form-group'));

    formGroups.forEach((group) => {
      group.classList.remove('error');
      const errorMsg = group.querySelector('.error-message');
      if (errorMsg) {
        errorMsg.style.display = 'none';
      }
    });

    const requiredFields = Array.from(form.querySelectorAll('input[required], select[required]'));
    const password = form.querySelector('input[name="password"]');
    const confirmPassword = form.querySelector('input[name="password_confirmation"]');

    const checkedYear = yearRadios.some((radio) => radio.checked);
    if (!checkedYear) {
      const yearGroup = form.querySelector('.year-check')?.closest('.form-group');
      if (yearGroup) {
        yearGroup.classList.add('error');
        const errorMsg = yearGroup.querySelector('.error-message');
        if (errorMsg) {
          errorMsg.style.display = 'block';
        }
      }
      isValid = false;
    }

    requiredFields.forEach((field) => {
      const parentGroup = field.closest('.form-group');
      if (!parentGroup) {
        return;
      }

      if (field.type === 'checkbox' && !field.checked) {
        parentGroup.classList.add('error');
        const errorMsg = parentGroup.querySelector('.error-message');
        if (errorMsg) {
          errorMsg.style.display = 'block';
        }
        isValid = false;
        return;
      }

      if (field.type !== 'radio' && field.type !== 'checkbox' && !field.value.trim()) {
        parentGroup.classList.add('error');
        const errorMsg = parentGroup.querySelector('.error-message');
        if (errorMsg) {
          errorMsg.style.display = 'block';
        }
        isValid = false;
      }
    });

    if (password && password.value.length < 8) {
      const passwordGroup = password.closest('.form-group');
      if (passwordGroup) {
        passwordGroup.classList.add('error');
        const errorMsg = passwordGroup.querySelector('.error-message');
        if (errorMsg) {
          errorMsg.style.display = 'block';
        }
      }
      isValid = false;
    }

    if (password && confirmPassword && password.value !== confirmPassword.value) {
      const confirmGroup = confirmPassword.closest('.form-group');
      if (confirmGroup) {
        confirmGroup.classList.add('error');
        const errorMsg = confirmGroup.querySelector('.error-message');
        if (errorMsg) {
          errorMsg.style.display = 'block';
        }
      }
      isValid = false;
    }

    if (!isValid) {
      event.preventDefault();
    }
  });

  const container = document.querySelector('.container');
  if (container) {
    setTimeout(() => {
      container.classList.add('page-entrance');
    }, 100);
  }
});

