        <div class="row">
            <div class="col-12">
                <div class="form-group form-group-default">
                    <label>Medio:</label>
                    <div class='radio radio-success input-group'><input id='medio0' type='radio' name='bqCampo_medio@medio' value='Chat'>
                <label for='medio0' class='mr-3'>
                    Chat
                </label><input id='medio1' type='radio' name='bqCampo_medio@medio' value='Correo'>
                <label for='medio1' class='mr-3'>
                    Correo
                </label><input id='medio2' type='radio' name='bqCampo_medio@medio' value='Pagina Web'>
                <label for='medio2' class='mr-3'>
                    Pagina Web
                </label><input id='medio3' type='radio' name='bqCampo_medio@medio' value='Saia'>
                <label for='medio3' class='mr-3'>
                    Saia
                </label><input id='medio4' type='radio' name='bqCampo_medio@medio' value='Telefono'>
                <label for='medio4' class='mr-3'>
                    Telefono
                </label></div>

                    <input type="hidden" name="bqCondicional_medio" value="=">
                    <input type="hidden" name="bqComparador_medio" value="y" />
                    <input type="hidden" name="bqTipo_medio" value="radio" />
                    
                    <input type="hidden" name="bqTabla_medio" value="campo_seleccionados medio" />
                    <input type="hidden" name="bqRelacionTabla_medio" value="medio.fk_documento=d.iddocumento AND medio.fk_campos_formato=9858" />
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
                    <input type="hidden" name="bqRelacionTabla_anexos" value="anexos.documento_iddocumento=d.iddocumento AND anexos.campos_formato=9834" />
                </div>
            </div>
        </div>