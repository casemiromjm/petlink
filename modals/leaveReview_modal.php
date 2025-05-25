<div id="leaveReviewModal" class="modal-overlay" style="display:none;">
  <div class="modal-content review-modal-content">
    <button id="closeLeaveReviewModal" class="modal-close" aria-label="Fechar">&times;</button>
    <h3>Deixar Avaliação</h3>
    <form id="leaveReviewForm" action="../actions/action_leaveReview.php" method="post">
      <input type="hidden" name="order_id" value="<?= htmlspecialchars((string)($latestOrder['request_id'] ?? '')) ?>">
      <label for="rating">Avaliação:</label>
      <select name="rating" id="rating" required>
        <option value="">Selecione</option>
        <?php for ($i = 5; $i >= 1; $i--): ?>
          <option value="<?= $i ?>"><?= $i ?> estrela<?= $i > 1 ? 's' : '' ?></option>
        <?php endfor; ?>
      </select>
      <label for="comment">Comentário:</label>
      <textarea name="comment" id="comment" rows="4" required></textarea>
      <button type="submit" class="review-submit-btn">Submeter avaliação</button>
    </form>
  </div>
</div>
<script>
  document.getElementById('openLeaveReviewModalBtn').onclick = function() {
    document.getElementById('leaveReviewModal').style.display = 'flex';
  };
  document.getElementById('closeLeaveReviewModal').onclick = function() {
    document.getElementById('leaveReviewModal').style.display = 'none';
  };
</script>