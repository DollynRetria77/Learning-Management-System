
<footer>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="text-footer">
          Learning Management System
        </div>
      </div>
    </div>
  </div>
</footer>
<!-- Modal : Liste Etudiant -->
<button style="display:none" type="button" id="btn-popup" class="btn btn-primary" data-toggle="modal" data-target="#listeEtudiant">
  Launch demo modal
</button>
<div class="modal fade" id="listeEtudiant" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Liste des etudiants</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="listeDesEtudiants">
        </div>
      </div>
    </div>
  </div>
</div>
<!--/ Modal : Liste Etudiant -->
<?php wp_footer(); ?>
</body>
</html>