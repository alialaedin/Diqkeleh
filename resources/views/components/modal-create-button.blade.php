@props([  
  'modalId',  
  'title',  
  'isSmall' => true,  
  'hasIcon' => true  
])  

<button  
  {{ $attributes->merge([  
    'class' => collect([  
      'btn',  
      'btn-indigo',  
      $isSmall ? 'btn-sm' : null, 
    ])->filter()->join(' '),
    'data-toggle' => 'modal',  
    'data-target' => '#' . $modalId  
  ]) }}  
>  
  {{ $title }}  
  @if ($hasIcon)  
    <i class="fa fa-plus mr-1"></i>  
  @endif  
</button> 