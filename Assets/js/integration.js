function toggleIP() {
    var dhcpCheckbox = document.getElementById('dhcp');
    var ipField = document.getElementById('ipFeld');
    dhcpCheckbox.checked ? ipField.style.display = 'none' : ipField.style.display = 'flex';
}
function togglePW() {
    var passwordFeld = document.getElementById('password');
    var pwVisibleCheckbox = document.getElementById('pwVisible');
    passwordFeld.type = pwVisibleCheckbox.checked ? 'text' : 'password';
}