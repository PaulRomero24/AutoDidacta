### INPUTS ###
#Los inputs siempre van a retornar datos de tipo "string"

saludos = input("Como te llamas? ")

print(f"Como estas {saludos}?")


input_numeros = input("Escriba un numero para multiplicar x2  :")

# Si queremos darle un tipo especifico al "string" del input debemos anunciarlo con la funcion antes del "dato".
resultado = int(input_numeros) * 2 

print(f"El resultado de {input_numeros} es {resultado}")