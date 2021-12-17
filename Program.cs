


using Microsoft.Extensions.Options;
var builder = WebApplication.CreateBuilder(args);

// Add services to the container.
builder.Services.Configure<MapApi.Models.IntegerSpiralDatabaseSettings>(builder.Configuration.GetSection(nameof(MapApi.Models.IntegerSpiralDatabaseSettings)));
builder.Services.AddSingleton<MapApi.Models.IIntegerSpiralDatabaseSettings>(sp=>sp.GetRequiredService<IOptions<MapApi.Models.IntegerSpiralDatabaseSettings>>().Value);
builder.Services.AddSingleton<MapApi.Services.SpiralServices>();
builder.Services.AddControllers();
// Learn more about configuring Swagger/OpenAPI at https://aka.ms/aspnetcore/swashbuckle
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

var app = builder.Build();

// Configure the HTTP request pipeline.
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}

app.UseHttpsRedirection();

app.UseAuthorization();

app.MapControllers();

app.Run();
