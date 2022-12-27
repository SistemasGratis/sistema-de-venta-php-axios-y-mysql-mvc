<form id="frmConfiguracion" autocomplete="off">
    <div class="card mb-2">
        <div class="card-body">
            <h4 class="text-center">Datos de la empresa</h4>
            <hr>
            <input type="hidden" id="id" name="id" value="1">
            <div class="row">
                <div class="col-md-4">
                    <label for="">Nombre <span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                        </div>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="">Correo <span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="">Teléfono <span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        </div>
                        <input type="number" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
                    </div>
                </div>
                <div class="col-md-6">                    
                    <div class="form-group">
                        <label for="">Dirección <span class="text-danger">*</span></label>
                        <textarea id="direccion" class="form-control" name="direccion" rows="3" placeholder="Dirección"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary" id="btn-save">Modificar</button>
        </div>
    </div>
</form>