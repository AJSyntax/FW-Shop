class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    public function designs()
    {
        return $this->hasMany(Design::class);
    }
} 