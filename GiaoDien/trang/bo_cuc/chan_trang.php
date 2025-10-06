        </div> <!-- Đóng thẻ .main-content-wrapper -->
    </main>

<footer class="footer-custom text-white text-center p-3 mt-auto">
    <div class="main-content-wrapper">
        <p class="mb-0">&copy; <?php echo date('Y'); ?> táo ngon.</p>
    </div>
</footer>

<script src="TaiNguyen/js/bootstrap.bundle.min.js"></script>
<!-- Floating Contact Buttons -->
<div class="contact-fab">
  <button id="toTop" class="fab-item backtop" type="button" data-title="Về đầu trang" aria-label="Về đầu trang">
    ↑
  </button>
</div>

<script>
  const toTop = document.getElementById('toTop');
  toTop.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
</script>

</body>
</html>