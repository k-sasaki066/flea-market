const select = document.getElementById('select');

const savedValue = sessionStorage.getItem('selectedOption');
if (savedValue) {
    select.value = savedValue;
    selectValue.innerHTML = select.options[select.selectedIndex].innerHTML;
}

select.addEventListener('change', () => {
    const selectValue = document.getElementById('selectValue');
    selectValue.innerHTML = select.options[select.selectedIndex].innerHTML;
    sessionStorage.setItem('selectedOption', select.value);
});