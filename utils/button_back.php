<style>
  .back_button {
    margin-top: 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    width: 100%;
  }

  .back_button button {
    margin-right: auto;
    padding: 0.5rem 1rem;
    background-color: #f57c00;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
</style>

<div class="back_button">
  <button onclick="goBack()">
    << Kembali Ke Halaman Utama</button>
      <script>
        function goBack() {
          window.history.back();
        }
      </script>
</div>