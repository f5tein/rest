<!-- <CasesSnippet> -->
@extends('layout')

@section('content')

<h2 class="pb-20">
    Covid-19 Cases
</h2>
<div class="form-row">
    <div class="col">
        <div class="form-group">
            <input type="hidden" id="stateId" value="{{ $state }}">
            <label for="state">State</label>

            <select class="form-control" id="state" name="state">
                <option value=""></option>
                <option value="AC">Acre</option>
                <option value="AL">Alagoas</option>
                <option value="AP">Amapá</option>
                <option value="AM">Amazonas</option>
                <option value="BA">Bahia</option>
                <option value="CE">Ceará</option>
                <option value="DF">Distrito Federal</option>
                <option value="ES">Espírito Santo</option>
                <option value="GO">Goiás</option>
                <option value="MA">Maranhão</option>
                <option value="MT">Mato Grosso</option>
                <option value="MS">Mato Grosso do Sul</option>
                <option value="MG">Minas Gerais</option>
                <option value="PA">Pará</option>
                <option value="PB">Paraíba</option>
                <option value="PR">Paraná</option>
                <option value="PE">Pernambuco</option>
                <option value="PI">Piauí</option>
                <option value="RJ">Rio de Janeiro</option>
                <option value="RN">Rio Grande do Norte</option>
                <option value="RS">Rio Grande do Sul</option>
                <option value="RO">Rondônia</option>
                <option value="RR">Roraima</option>
                <option value="SC">Santa Catarina</option>
                <option value="SP">São Paulo</option>
                <option value="SE">Sergipe</option>
                <option value="TO">Tocantins</option>
            </select>
        </div>
    </div>
</div>

<div class="form-row">
    <div class="col">
      <div class="form-group">
        <label>Start</label>
        <input type="text" class="form-control" name="startDate" id="startDate" 
        value="{{ \Carbon\Carbon::parse($startDate)->format('yy-m-d') }}"/>
      </div>
      @error('startDate')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror
    </div>
    <div class="col">
      <div class="form-group">
        <label>End</label>
        <input type="text" class="form-control" name="endDate" id="endDate"value="{{ \Carbon\Carbon::parse($endDate)->format('yy-m-d') }}"/>
      </div>
      @error('endDate')
        <div class="alert alert-danger">{{ $message }}</div>
      @enderror
    </div>
  </div>

</div>

<div class="form-row">
    <a title="Search" id="search" class="btn btn-info btn-sm btn-block">
    <i class="fa fa-search"></i> Search 
    </a>
</div>

<table class="table">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th width="30%">City</th>
            <th width="20%">Population</th>
            <th width="20%">Confirmed</th>
            <th width="20%">Confirmed per 100.000 inhabitants</th>
            <th width="20%">Date</th>
        </tr>
    </thead>
    <tbody>
        @isset($cases)
            @foreach($cases as $key => $case)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $case["city"] }}</td>
                    <td>{{ $case["estimated_population"] }}</td>
                    <td>{{ $case["confirmed"] }}</td>
                    <td>{{ $case["confirmed_per_100k_inhabitants"] }}</td>
                    <td>{{ \Carbon\Carbon::parse($case["date"])->format('d/m/yy') }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
@endsection
<!-- </CasesSnippet> -->

<script type="text/javascript">
window.addEventListener('load', function() {
    $(document).ready(function() {
        $('#state').val($('#stateId').val());
        $('#search').on("click", function(e){
            e.preventDefault();

                url = "http://localhost:8888/rest/"
                url += $("#state").val() + "/";
                url += $("#startDate").val() + "/";
                url += $("#endDate").val();
            window.location.replace(url);

            console.log("state", $("#state").val());
            console.log("startDate", $("#startDate").val());
            console.log("endDate", $("#endDate").val());
        });
    });
});
</script>

<script type="text/javascript">
    
</script>
