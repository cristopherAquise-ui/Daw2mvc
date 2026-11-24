<?php
/**
 * Oferta base.
 *
 * Cada tipo de oferta (descuento, 2x1, combo, código) hereda de esta clase
 * e implementa su propia forma de calcular el descuento. Así se cumple el
 * requisito de polimorfismo sin complicar el resto de la aplicación.
 */
abstract class Oferta
{
    protected $id;
    protected $nombre;
    protected $descripcion;
    protected $tipo;
    protected $ambito;
    protected $categoria_id;
    protected $valor;
    protected $codigo;
    protected $cantidad_minima;
    protected $importe_minimo;
    protected $fecha_inicio;
    protected $fecha_fin;
    protected $activa;
    protected $fecha_creacion;
    protected $fecha_actualizacion;

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function getTipo() { return $this->tipo; }
    public function getAmbito() { return $this->ambito; }
    public function getCategoriaId() { return $this->categoria_id; }
    public function getValor() { return $this->valor; }
    public function getCodigo() { return $this->codigo; }
    public function getCantidadMinima() { return $this->cantidad_minima; }
    public function getImporteMinimo() { return $this->importe_minimo; }
    public function getFechaInicio() { return $this->fecha_inicio; }
    public function getFechaFin() { return $this->fecha_fin; }
    public function getActiva() { return $this->activa; }
    public function getFechaCreacion() { return $this->fecha_creacion; }
    public function getFechaActualizacion() { return $this->fecha_actualizacion; }

    /**
     * Calcula el descuento que corresponde a esta oferta.
     *
     * @param float $subtotal Importe sobre el que se aplica.
     * @param array $lineas   Líneas con precio_unitario y cantidad.
     */
    abstract public function calcularDescuento(float $subtotal, array $lineas = []): float;

    /**
     * Texto corto para mostrar en la tarjeta de la oferta.
     */
    public function etiqueta(): string
    {
        return strtoupper((string) $this->tipo);
    }

    /**
     * Rellena la oferta con una fila de la base de datos.
     */
    public function hydrate(array $fila): void
    {
        foreach ($fila as $campo => $valor) {
            if (property_exists($this, $campo)) {
                $this->$campo = $valor;
            }
        }
    }

    /**
     * Devuelve la mejor oferta aplicable a un producto suelto.
     * Las ofertas de tipo combo o código no se aplican a una unidad suelta.
     */
    public static function mejorOfertaProducto(Producto $producto, array $ofertas): array
    {
        $precio = (float) $producto->getPrecio();
        $mejor  = [
            'descuento'    => 0.0,
            'precio_final' => $precio,
            'oferta'       => null,
        ];

        foreach ($ofertas as $oferta) {
            $tipo = $oferta->getTipo();

            if ($tipo === 'codigo' || $tipo === 'combo') {
                continue;
            }

            $descuento = 0.0;

            if ($tipo === 'descuento') {
                $descuento = round($precio * ((float) $oferta->getValor() / 100), 2);
            }

            // En 2x1 no hay descuento por una sola unidad, pero la oferta se muestra.
            if ($descuento > $mejor['descuento']) {
                $mejor = [
                    'descuento'    => $descuento,
                    'precio_final' => max(0, $precio - $descuento),
                    'oferta'       => $oferta,
                ];
            } elseif ($mejor['oferta'] === null && $tipo === '2x1') {
                $mejor['oferta'] = $oferta;
            }
        }

        return $mejor;
    }

    /**
     * Valida un código promocional y calcula su descuento.
     */
    public static function descuentoCodigo(string $codigo, float $subtotal): array
    {
        $codigo = trim($codigo);

        if ($codigo === '') {
            return [
                'valido'    => false,
                'descuento' => 0.0,
                'oferta'    => null,
                'mensaje'   => '',
            ];
        }

        $oferta = OfertaDAO::getByCodigo($codigo);

        if (!$oferta) {
            return [
                'valido'    => false,
                'descuento' => 0.0,
                'oferta'    => null,
                'mensaje'   => 'El código no es válido o ha caducado.',
            ];
        }

        $minimo = (float) $oferta->getImporteMinimo();

        if ($subtotal < $minimo) {
            return [
                'valido'    => false,
                'descuento' => 0.0,
                'oferta'    => $oferta,
                'mensaje'   => 'Este código requiere un pedido mínimo de ' . precio($minimo) . '.',
            ];
        }

        return [
            'valido'    => true,
            'descuento' => $oferta->calcularDescuento($subtotal),
            'oferta'    => $oferta,
            'mensaje'   => 'Código aplicado correctamente.',
        ];
    }

    /**
     * Texto de la etiqueta que se muestra en la tarjeta de la oferta.
     */
    public static function etiquetaTexto(Oferta $oferta): string
    {
        if ($oferta->getTipo() === 'combo') {
            return 'COMBO ' . precio($oferta->getValor());
        }

        return $oferta->etiqueta();
    }
}
