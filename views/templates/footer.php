<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASEURL; ?>/js/script.js"></script>

<script>
function toggleFamilyCode() {
    var roleAnak = document.getElementById('role_anak');
    var codeInput = document.getElementById('family_code_input');

    if (roleAnak.checked) {
        codeInput.style.display = 'block';
        document.getElementById('input_family_code').required = true;
    } else {
        codeInput.style.display = 'none';
        document.getElementById('input_family_code').required = false;
    }
}
</script>
</body>

</html>