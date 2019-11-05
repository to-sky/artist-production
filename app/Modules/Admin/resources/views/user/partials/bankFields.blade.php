<div class="form-group col-md-12">
    {!! Form::label('settings[company_bank_name]', __('Bank name')) !!}
    {!! Form::text('settings[company_bank_name]', old('settings.company_bank_name', setting('company_bank_name')), ['class'=>'form-control', 'placeholder'=> __('Bank name')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_bank_account]', __('Account number')) !!}
  {!! Form::text('settings[company_bank_account]', old('settings.company_bank_account', setting('company_bank_account')), ['class'=>'form-control', 'placeholder'=> __('Account number')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_bank_blz]', __('BLZ')) !!}
  {!! Form::text('settings[company_bank_blz]', old('settings.company_bank_blz', setting('company_bank_blz')), ['class'=>'form-control', 'placeholder'=> __('BLZ')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_bank_iban]', __('IBAN')) !!}
  {!! Form::text('settings[company_bank_iban]', old('settings.company_bank_iban', setting('company_bank_iban')), ['class'=>'form-control', 'placeholder'=> __('IBAN')]) !!}
</div>

<div class="form-group col-md-12">
  {!! Form::label('settings[company_bank_bic]', __('BIC (SWIFT-code)')) !!}
  {!! Form::text('settings[company_bank_bic]', old('settings.company_bank_bic', setting('company_bank_bic')), ['class'=>'form-control', 'placeholder'=> __('BIC (SWIFT-code)')]) !!}
</div>