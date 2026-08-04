#
# A) PEDIRLE A UN USUARIO QUE DIGA UNA FRASE y:
# -calcular cuanto tarda en decirla 
# -Cuantas palabras dijo

frase = input("Escriba una frase por favor :")
#Creamos una lista con todas las palabras escritas con el parametro de espacio(" ") como separador
palabras_separadas = frase.split(" ")

#Hacemos conteo de las palabras
cantidad_palabras = len(palabras_separadas)

if cantidad_palabras > 120:
    print(f"Es un monton de palabras. mira: {cantidad_palabras} palabras y tardarias {round((cantidad_palabras / 2),1)} seg en decirlas")
else:
    #Le mostramos al usuario cuanto tardaria
    print(f"Escribiste {cantidad_palabras} y tardarias {cantidad_palabras / 2} seg en decirlas")


