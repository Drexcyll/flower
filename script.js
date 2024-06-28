let hamburgerMenu = document.querySelector('#menu-btn');
let userbtn = document.querySelector('#user-btn');

hamburgerMenu.addEventListener('click', function(){
    let nav = document.querySelector('.navbar');
    nav.classList.toggle('active');
})
userbtn.addEventListener('click', function(){
    let userBox = document.querySelector('.user-box');
    userBox.classList.toggle('active');
})

function editProduct(id, name, price, image) {
    document.getElementById('update_id').value = id;
    document.getElementById('update_name').value = name;
    document.getElementById('update_price').value = price;
    document.getElementById('update_image').src = image;
    document.getElementById('editModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}
