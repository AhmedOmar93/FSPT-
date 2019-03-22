<input id="{{option.id}}" type="radio" name="choice"  value="{{ option.description }}"  ng-click="select_choice(option.id)"    ng-if="option.id==selectedOptionId" checked />

{{ option.description }}

<div style='margin-left: 10px;' class='btn btn-danger btn-xs' ng-if="Item.user_id==userId" ng-click='delete_choice( option.id  )' >X</div>

</br>

</div>