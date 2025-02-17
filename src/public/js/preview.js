document.addEventListener("DOMContentLoaded", function () {
    const figureImage = document.querySelector('#figureImage');
    const input = document.querySelector('input[name="image_url"]');

    if (!figureImage.getAttribute('src')) {
        figureImage.style.display = 'none';
    }

    input.addEventListener('change', (event) => {
        const [file] = event.target.files;

        if (!file) {
            figureImage.style.display = 'none';
            figureImage.removeAttribute('src');
            return;
        }

        const fileReader = new FileReader();
        fileReader.onload = () => {
            figureImage.setAttribute('src', fileReader.result);
            figureImage.style.display = 'block';
        };
        fileReader.readAsDataURL(file);
    });
});