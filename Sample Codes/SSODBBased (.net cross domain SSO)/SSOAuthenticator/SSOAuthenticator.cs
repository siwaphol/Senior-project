using System;
using System.Linq;


namespace SSOAuthenticator
{
    public static class SSOAuthenticator
    {
        public static void SetAppToken(SSOAppsToken ssoAppsToken, string appName)
        {
            using (var entities2 = new SSODBEntities2())
            {
                var ssoApps = from apps in entities2.SSOEnabledApps
                              where apps.APPName == appName
                              select apps;
                if (!ssoApps.Any())
                    throw new Exception("No Apps Found");
                ssoAppsToken.APPId = ssoApps.First().APPId;
                entities2.SSOAppsTokens.Add(ssoAppsToken);
                entities2.SaveChanges();
            }
        }

        public static SSOAppsToken GetAppToken(Guid tokenId)
        {
           using(var entities2 = new SSODBEntities2())
           {
               var ssoAppToken = (from appToken in entities2.SSOAppsTokens
                                 where appToken.TokenId == tokenId
                                 select appToken).First();

               //Delete the entry after getting the token, so reusing is denied
               if (ssoAppToken != null)
               {
                   entities2.SSOAppsTokens.Remove(ssoAppToken);
                   entities2.SaveChanges();
               }

               return ssoAppToken;
           }
        }



    }
}
