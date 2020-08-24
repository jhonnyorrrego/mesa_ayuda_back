        <div class="row">
              <div class="col-12">
                  <div class="form-group form-group-default">
                      <label>Descripción:</label>
                      <textarea class ="form-control" name="bqCampo_ft@descripcion"></textarea>

                      <input type="hidden" name="bqCondicional_descripcion" value="like">
                      <input type="hidden" name="bqComparador_descripcion" value="y" />
                  </div>
              </div>
          </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group form-group-default">
                    <label>Anexos:</label>
                    <div class='checkbox check-success input-group'>
            <input type='checkbox' id='anexos' name='bqCampo_anexos' value='1'>
            <label for='anexos' class='mr-3'>
                Contiene archivos
            </label>
        </div>
                    
                    <input type="hidden" name="bqTipo_anexos" value="adjunto" />
                    
                    <input type="hidden" name="bqTabla_anexos" value="anexos anexos" />
                    <input type="hidden" name="bqRelacionTabla_anexos" value="anexos.documento_iddocumento=d.iddocumento AND anexos.campos_formato=9834" />
                </div>
            </div>
        </div>