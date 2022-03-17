<?php
$paginacao='<ul class="pagination">';

if ($pag>1){
  $paginacao .= '<li><a href="'.$pagina_atual.'/'.$ant.'">&laquo;</a></li>';
}
  
if ($ultima_pag <= 5 and $ultima_pag>1){
  for ($i=1; $i< $ultima_pag+1; $i++){
    if ($i == $pag){
      $paginacao .= '<li class="active"><a href="'.$pagina_atual.'/'.$i.'">'.$i.'</a></li>';        
    } else {
      $paginacao .= '<li><a href="'.$pagina_atual.'/'.$i.'">'.$i.'</a></li>';  
    }
  }
}

if ($ultima_pag > 5){
  if ($pag < 1 + (2 * $adjacentes)){
    for ($i=1; $i< 2 + (2 * $adjacentes); $i++){
      if ($i == $pag){
        $paginacao .= '<li class="active"><a href="'.$pagina_atual.'/'.$i.'">'.$i.'</a></li>';        
      } else {
        $paginacao .= '<li><a href="'.$pagina_atual.'/'.$i.'">'.$i.'</a></li>';  
      }
    }
    $paginacao .= '<li class="disabled"><a href="'.$pagina_atual.'/'.$i.'">...</a></li>';
    $paginacao .= '<li><a href="'.$pagina_atual.'/'.$penultima.'">'.$penultima.'</a></li>';
    $paginacao .= '<li><a href="'.$pagina_atual.'/'.$ultima_pag.'">'.$ultima_pag.'</a></li>';
  
  }elseif($pag > (2 * $adjacentes) && $pag < $ultima_pag - 3){
  
	$paginacao .= '<li><a href="'.$pagina_atual.'/1">1</a></li>';        
    $paginacao .= '<li><a href="'.$pagina_atual.'/1">2</a></li><li class="disabled"><a href="'.$pagina_atual.'/'.$i.'">...</a></li>';  
    for ($i = $pag-$adjacentes; $i<= $pag + $adjacentes; $i++){
      if ($i == $pag){
        $paginacao .= '<li class="active"><a href="'.$pagina_atual.'/'.$i.'">'.$i.'</a></li>';        
      } else {
        $paginacao .= '<li><a href="'.$pagina_atual.'/'.$i.'">'.$i.'</a></li>';  
      }
    }
    $paginacao .= '<li class="disabled"><a href="'.$pagina_atual.'/'.$i.'">...</a></li>';
    $paginacao .= '<li><a href="'.$pagina_atual.'/'.$penultima.'">'.$penultima.'</a></li>';
    $paginacao .= '<li><a href="'.$pagina_atual.'/'.$ultima_pag.'">'.$ultima_pag.'</a></li>';
  } else {
    $paginacao .= '<li><a href="'.$pagina_atual.'/1">1</a></li>';        
    $paginacao .= '<li><a href="'.$pagina_atual.'/1">2</a></li><li class="disabled">...</li>';  
    for ($i = $ultima_pag - (4 + (2 * adjacentes)); $i <= $ultima_pag; $i++){
      if ($i == $pag){
        $paginacao .= '<li class="active"><a href="'.$pagina_atual.'/'.$i.'">'.$i.'</a></li>';        
      } else {
        $paginacao .= '<li><a href="'.$pagina_atual.'/'.$i.'">'.$i.'</a></li>';  
      }
    }
  }
}


if ($prox <= $ultima_pag && $ultima_pag > 2){
	$paginacao .= '<li><a href="'.$pagina_atual.'/'.$prox.'">&raquo;</a></li>';
}

$paginacao .="</ul>";
if ($ultima_pag>1){echo $paginacao;}
?>