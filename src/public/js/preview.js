const input = document.querySelector('input[name="image_url"]')
const figure = document.querySelector('#figure')
const figureImage = document.querySelector('#figureImage')

input.addEventListener('change', (event) => {
    const [file] = event.target.files;
    if (!file) {
        figure.style.display = 'none';
        return;
    }

    const fileReader = new FileReader();
    fileReader.onload = () => {
        figureImage.setAttribute('src', fileReader.result);
        figure.style.display = 'block';
    };
    fileReader.readAsDataURL(file);
});