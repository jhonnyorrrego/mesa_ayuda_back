        <div class="row">
            <div class="col-12">
                <div class="form-group form-group-default form-group-default-select2">
                    <label>Clasificación:</label>
                    <select class='full-width' name='bqCampo_pre_clasificacion@pre_clasificacion' id='pre_clasificacion'>
        <option value=''>Por favor seleccione...</option><option value=' '> </option></select>

                    <input type="hidden" name="bqCondicional_pre_clasificacion" value="=">
                    <input type="hidden" name="bqComparador_pre_clasificacion" value="y" />
                    <input type="hidden" name="bqTipo_pre_clasificacion" value="select" />
                    
                    <input type="hidden" name="bqTabla_pre_clasificacion" value="campo_seleccionados pre_clasificacion" />
                    <input type="hidden" name="bqRelacionTabla_pre_clasificacion" value="pre_clasificacion.fk_documento=d.iddocumento AND pre_clasificacion.fk_campos_formato=9427" />
                </div>
            </div>
        </div>
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
                    <input type="hidden" name="bqRelacionTabla_anexos" value="anexos.documento_iddocumento=d.iddocumento AND anexos.campos_formato=9423" />
                </div>
            </div>
        </div>
<script>
            $(document).ready(function(){
                $('#pre_clasificacion').select2();
            })
            </script>