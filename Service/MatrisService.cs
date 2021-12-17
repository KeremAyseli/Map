namespace MapApi.Services
{
    public class MatrisService
    {
        int direction = 1, x = 0, y = 0, tur = 0, adet = 0;

        public MatrisService(int x,int y){
            Npoint[][] array1=newMatris(x,y);
            MakeMatris(array1, array1.Length, array1[0].Length);
        }
        public Npoint[][] newMatris(int x, int y)
        {
            Npoint[][] array1 = new Npoint[10][];
            for (int i = 0; i < array1.Length; i++)
            {
                array1[i] = new Npoint[10];
                for (int j = 0; j < array1[i].Length; j++)
                {
                    array1[i][j] = new Npoint();
                }
            }
            return array1;
        }
        void MakeMatris(Npoint[][] matris, int matrisX, int matrisY)
        {
            if (direction == 1)
            {
                if (y == matrisY)
                {
                    direction = -1; y--;
                }
                else
                {
                    if (matris[x][y != matrisY - 1 ? y + 1 : matrisY - 1].isOpen)
                    {
                        assigment(matris, x, y, direction);
                        y++;
                    }
                    else
                    {
                        direction = -1;
                    }
                }
            }
            if (direction == -1)
            {

                x++;
                if (x == matrisX)
                {
                    direction = 0; x--;
                }
                else
                {
                    if (matris[x != matrisX - 1 ? x + 1 : matrisX - 1][y].isOpen)
                    {
                        assigment(matris, x, y, direction);
                    }
                    else
                    {
                        direction = 0;
                    }

                }
            }

            if (direction == 0)
            {

                {
                    y--;
                    if (y < 0)
                    {
                        direction = 2; y++;
                    }
                    else
                    {
                        if (matris[x][y <= 0 ? 0 : y - 1].isOpen)
                        { assigment(matris, x, y, direction); }
                        else
                        { direction = 2; }
                    }
                }
            }
            if (direction == 2)
            {

                x--;
                if (x < 0)
                {
                    direction = 1; x++;
                }
                else
                {
                    if (matris[x <= 0 ? 0 : x - 1][y].isOpen)
                    { assigment(matris, x, y, direction); }
                    else
                    { direction = 1; }
                }

            }

            tur++;
            adet++;
            if (tur >= 100)
            {
                Console.WriteLine(tur);
                printMatris(matris);
            }
            else
            {
                MakeMatris(matris, matrisX, matrisY);
            }

        }
        bool assigment(Npoint[][] matris, int x, int y, int direction)
        {
            Console.WriteLine("y:{0} x:{1} yön:{2} durum:{3}", y, x, direction, matris[x][y].isOpen);
            Console.WriteLine("Şu anki sayı:----------------" + adet);
            if (matris[x][y].isOpen == true)
            {
                matris[x][y].number = adet;
                matris[x][y].isOpen = false;
                return true;
            }
            else
            {
                return false;
            }
        }
        public void printMatris(Npoint[][] array1)
        {
            for (int i = 0; i < array1.Length; i++)
            {
                for (int j = 0; j < array1[i].Length; j++)
                {
                    Console.Write(array1[i][j].number + " ");
                }
                Console.WriteLine();
            }
        }
    }

}