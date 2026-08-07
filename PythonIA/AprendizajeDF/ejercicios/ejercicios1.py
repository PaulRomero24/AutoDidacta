
#Promedio de duracion
otros_cursos_min = 2.5
otros_cursos_max = 7
otros_cursos_promedio = 4
dalto_curso = 1.5


#Duracion de crudo
crudos_promedios = 5
crudo_dalto = 3.5


#Diferencia de duracion

diferencia_con_min = 100 - (dalto_curso / otros_cursos_min * 100)

#Esta es una forma matematica de trabajar con los numeros decimales que querramos: 
#Se multiplica el "dato" por "x" ceros que querramos agregar para que la división entera (descarta el residuo) no pierda información, y luego dividimos en la división común para
#reducir la escala y obtener los decimales deseados.
diferencia_con_max = 100 - (dalto_curso * 1000 // otros_cursos_max / 10)
#Sino usamos la funcion round,que hace lo mismo: round("dato",cantidad de decimales que querramos)

diferencia_con_promedio = 100 - (dalto_curso / otros_cursos_promedio * 100)

#Tiempo removido.
tiempo_vacio_promedio = 100 - round((otros_cursos_promedio / crudos_promedios)*100,1)
tiempo_vacio_dalto = 100 - round((dalto_curso/crudo_dalto)*100,1)

print(f"El curso de Dalto dura un {diferencia_con_min}% menos que el mas rapido")
print(f"El curso de Dalto dura un {diferencia_con_max}% menos que el mas largo")
print(f"El curso de Dalto dura un {diferencia_con_promedio}% menos que el mas promedio")
print("\n")
print(f"El crudo removido en un video promedio es de {tiempo_vacio_promedio}%")
print(f"El crudo removido en un video del dalto es de {tiempo_vacio_dalto}%")
print("\n")
print(f"Ver 10hs de el curso de Dalto equivale a ver {round((otros_cursos_promedio *100/dalto_curso/10),1)} horas de otros cursos")
print(f"Ver 10hs de otros cursos equivale a ver {round((dalto_curso *100/otros_cursos_promedio/10),1)} horas de este curso")